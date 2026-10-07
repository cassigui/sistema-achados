<?php

namespace App\Modules\Phones;

use Illuminate\Support\Facades\DB;
use App\Modules\Base\Services\ApiService;

class PhoneService
{
    public function __construct(Phone $model)
    {
        $this->model = $model;
        $this->api = new ApiService($this->model, $this->getCustomFilters(), $this->getCustomSorts());
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

    public function store(array $data, $phoneable_id, $phoneable_type)
    {
        try {
            DB::beginTransaction();

            $data['phoneable_id'] = $phoneable_id;
            $data['phoneable_type'] = $phoneable_type;

            $model = $this->model->create($data);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }

        return $model;
    }

    public function update(array $data, $id, $phoneable_id, $phoneable_type)
    {
        try {
            DB::beginTransaction();

            $data['phoneable_id'] = $phoneable_id;
            $data['phoneable_type'] = $phoneable_type;

            $model = $this->model->findOrFail($id);

            $model->update($data);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }

        return $model;
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $this->model->findOrFail($id)->delete();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }

        return true;
    }

    public function restore($id)
    {
        try {
            DB::beginTransaction();

            $this->model->onlyTrashed()->findOrFail($id)->restore();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }

        return true;
    }
}
