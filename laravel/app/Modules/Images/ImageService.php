<?php

namespace App\Modules\Images;

use App\Modules\Base\Services\ApiService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image as InterventionImage;

class ImageService
{
    protected $model;
    protected $api;
    protected $disk;
    protected $folder_temp;
    protected $folder_local;

    public function __construct(Image $model)
    {
        $this->model        = $model;
        $this->api          = new ApiService($this->model, $this->getCustomFilters(), $this->getCustomSorts());
        $this->disk         = Storage::disk('public'); // Armazenamento local (storage/app/public)
        $this->folder_temp  = storage_path('app/temp');
        $this->folder_local = 'images';
    }

    protected function getCustomFilters()
    {
        return [
            // 'chave' => function($query, $key, $input) {}
        ];
    }

    protected function getCustomSorts()
    {
        return [
            // 'coluna' => function($query, $column, $order) {}
        ];
    }

    public function store(array $data)
    {
        try {
            DB::beginTransaction();

            if (!empty($data['base64'])) {
                $base64 = $this->getExtensionBase64($data['base64']);
                $name   = Str::random(15) . '.' . $base64['extension'];
                
                // Salva temporariamente para manipulação
                $tempPath = $this->storeBase64($base64, $name);

                $image = InterventionImage::make($tempPath);
                $image->save($tempPath, 100);

                // Caminho relativo final dentro do disco local
                $relativePath = $this->folder_local . '/' . $data['imageable_type'] . '/' . $data['imageable_id'] . '_' . $name;

                // Move do temporário para o disco local definitivo
                $this->disk->put($relativePath, file_get_contents($tempPath));

                // Elimina o ficheiro temporário
                $this->delete_temp_file($tempPath);

                $data['path'] = $relativePath;
            }

            $model = $this->model->create($data);

            if (isset($data['thumbs']) && is_array($data['thumbs'])) {
                foreach ($data['thumbs'] as $thumb) {
                    $this->generateThumb($model->toArray(), $thumb, $data['imageable_id'] . '_' . $name);
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }

        return $model;
    }

    public function generateThumb(array $data, array $thumb, string $name)
    {
        $new_name = str_replace($name, $thumb['prefix'] . $name, $data['path']);

        // Obtém o caminho absoluto do ficheiro local já salvo
        $originalFullPath = $this->disk->path($data['path']);

        if (!file_exists($originalFullPath)) {
            return;
        }

        $image      = InterventionImage::make($originalFullPath);
        $_image_bkp = clone $image;

        $image->resize($thumb['width'], null, function ($constraint) {
            $constraint->aspectRatio();
        });

        if ($image->height() < $thumb['height']) {
            $image = clone $_image_bkp;
            $image->resize(null, $thumb['height'], function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        $image->crop($thumb['width'], $thumb['height'], 0, 0);

        // Garante que o diretório de destino das thumbs existe
        $thumbFullPath = $this->disk->path($new_name);
        $directory     = dirname($thumbFullPath);

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Salva diretamente no destino final local
        $image->save($thumbFullPath, 100);
    }

    public function destroy($id, $thumbs = null)
    {
        try {
            DB::beginTransaction();

            $model = $this->model->findOrFail($id);

            // Elimina o ficheiro principal do disco local
            if ($this->disk->exists($model->path)) {
                $this->disk->delete($model->path);
            }

            if ($thumbs != null) {
                foreach ($thumbs as $thumb) {
                    $this->destroyThumb($model->toArray(), $thumb);
                }
            }

            $model->delete();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }

        return true;
    }

    public function destroyThumb(array $data, array $thumb)
    {
        $path_thumb = str_replace(
            '/' . $data['imageable_id'] . '_',
            '/' . $thumb['prefix'] . $data['imageable_id'] . '_',
            $data['path']
        );

        if ($this->disk->exists($path_thumb)) {
            $this->disk->delete($path_thumb);
        }
    }

    public function delete_temp_file($path_temp)
    {
        if (!empty($path_temp) && file_exists($path_temp)) {
            unlink($path_temp);
        }
    }

    public function getExtensionBase64($image)
    {
        if (isset($image['base64'])) {
            $base = explode(',', $image['base64']);
        } else {
            $base = explode(',', $image);
        }

        $imageContent = $base[1];
        $extension    = str_replace('data:image/', '', $base[0]);
        $extension    = str_replace(';base64', '', $extension);

        return ['image' => $imageContent, 'extension' => $extension];
    }

    public function storeBase64(array $imageBase64, string $name)
    {
        if (!File::exists($this->folder_temp)) {
            File::makeDirectory($this->folder_temp, 0775, true);
        }

        $output_file = $this->folder_temp . '/' . $name;
        file_put_contents($output_file, base64_decode($imageBase64['image']));

        return $output_file;
    }

    public function storeImages(array $images, int $imageable_id, string $imageable_type)
    {
        foreach ($images as $image) {
            if (!empty($image['base64'])) {
                $image['imageable_id']   = $imageable_id;
                $image['imageable_type'] = $imageable_type;

                $this->store($image);
            }
        }
    }
}