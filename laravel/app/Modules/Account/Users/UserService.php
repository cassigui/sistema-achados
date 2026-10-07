<?php
namespace App\Modules\Account\Users;

use App\Modules\Account\AccountException;
use App\Modules\Account\Auth\AuthService;
use App\Modules\Account\Notifications\ResetPasswordNotification;
use App\Modules\Addressing\Addresses\AddressService;
use App\Modules\Base\Services\ApiService;
use App\Modules\Phones\PhoneService;
use Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class UserService
{
    public function __construct(User $model, AuthService $auth_service, PhoneService $phone_service, AddressService $address_service)
    {
        $this->model           = $model;
        $this->api             = new ApiService($this->model, $this->getCustomFilters());
        $this->auth            = $auth_service;
        $this->address_service = $address_service;
        $this->phone_service   = $phone_service;
    }

    protected function getCustomFilters()
    {
        return [
            // 'customer'     => function ($query, $key, $input) {
            //     if ($input == 'true') {
            //         return $query->whereHas($key);
            //     } else {
            //         return $query->whereDoesntHave($key);
            //     }
            // },
        ];
    }

    public function store(array $data, array $relations = [])
    {
        try {
            DB::beginTransaction();
            if (! empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            $user = $this->model->create($data);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }

        return $user;
    }

    public function update(array $data, int $id, array $relations = [])
    {
        try {
            DB::beginTransaction();

            if (empty($data['password'])) {
                unset($data['password']);
            } else {
                $data['password'] = Hash::make($data['password']);
            }

            $user = $this->model->findOrFail($id);

            $user->update($data);

            if (! empty($data['phones'])) {
                foreach ($data['phones'] as $phone) {
                    if ($phone['id'] > 0) {
                        $this->phone_service->update($phone, $phone['id'], $user->id, 'users');
                    } else {
                        $this->phone_service->store($phone, $user->id, 'users');
                    }
                }
            }

            if (! empty($data['addresses'])) {
                foreach ($data['addresses'] as $address) {
                    $this->address_service->storeOrUpdate($address, 'users', $user->id);
                }
            }

            if (! empty($data['notices'])) {
                $notices = [];
                foreach ($data['notices'] as $notice) {
                    if ($notice['checked'] === true) {
                        $notices[] = $notice['id'];
                    }
                }
                $user->notices()->sync($notices);
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }

        return $this->api->load($user, $relations);
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            if (auth()->user() && ($id == auth()->user()->id)) {
                throw new AccountException(500, "Não é possível remover o próprio usuário.");
            }

            $user = $this->model->findOrFail($id);

            if ($user->collaborator()->exists()) {
                throw new AccountException(500, "O colaborador " . $user->collaborator->name . " está vinculado a este usuário, remova-o primeiro.");
            }

            $user->delete();

            $result = $user->deleted_at->toDateTimeString();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }

        return $result;
    }

    public function restore($id)
    {
        try {
            DB::beginTransaction();

            $user = $this->model->onlyTrashed()->findOrFail($id);

            $query = $this->model->where('username', $user->username)->orWhere('email', $user->email);

            if ($query->exists()) {
                $old = $query->first();

                $old->update([
                    'username' => "{$old->username}2",
                    'email' => "{$old->email}2",
                ]);
            }

            $result = $user->restore();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }

        return $result;
    }

    public function sendResetLinkEmail(array $data = [])
    {
        if (! $this->model->where('email', $data['email'])->exists()) {
            throw new AccountException(400, 'O e-mail informado não foi encontrado em nossos registros.');
        }

        try {
            DB::beginTransaction();

            $email      = $data['email'];
            $token      = $this->generateResetToken();
            $created_at = now();

            DB::table('password_resets')->updateOrInsert(
                compact('email'),
                compact('email', 'token', 'created_at')
            );

            Notification::route('mail', $email)
                ->notify(new ResetPasswordNotification($token));

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    protected function generateResetToken()
    {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $token = "";

        for ($i = 0; $i < 6; $i++) {
            $token .= substr($chars, rand(0, strlen($chars)) - 1, 1);
        }

        if (DB::table('password_resets')->where('token', $token)->exists()) {
            return $this->generateResetToken();
        }

        return $token;
    }

    public function validateResetToken(array $data)
    {
        return DB::table('password_resets')
            ->where('email', $data['email'])
            ->where('token', $data['token'])
            ->exists();
    }

    public function updatePassword(string $email, string $token, string $password, array $relations = [])
    {

        if (! $this->validateResetToken(compact('email', 'token'))) {
            throw new AccountException(400, 'E-mail ou código inválidos.');
        }

        $user = $this->update(
            compact('password'),
            $this->model->where('email', $email)->firstOrFail()->id,
            $relations
        );

        $this->wipeResetToken($email);

        return $user;
    }

    protected function wipeResetToken(string $email)
    {
        try {
            DB::beginTransaction();

            DB::table('password_resets')->where('email', $email)->delete();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return true;
    }

    public function is_admin($access_level_id)
    {
        return $access_level_id < 2;
    }

    public function export(array $request): string
    {
        $request['relations'] = array_values(array_unique(array_merge(
            $request['relations'] ?? [],
            ['access_level']
        )));

        $users = $this->api->get($request);

        $header_style = 'background-color:#000000;color:#ffffff;font-weight:bold;text-align:center;border:1px solid #000000;padding:6px;min-width:130px;';
        $cell_style   = 'vertical-align:top;text-align:left;border:1px solid #000000;padding:5px;min-width:130px;background-color:#ffffff;color:#000000;';

        $columns = [
            'ID',
            'Nome',
            'Usuário',
            'E-mail',
            'Nível de Acesso',
            'Ativo',
            'Criado em',
        ];

        $html = '<table style="width:100%;border-collapse:collapse;font-size:12px;border:1px solid #000000;">';

        $html .= '<tr>';
        foreach ($columns as $column) {
            $html .= '<td style="' . $header_style . '">' . $column . '</td>';
        }
        $html .= '</tr>';

        if (count($users)) {
            foreach ($users as $user) {
                $html .= '<tr>';
                $html .= '<td style="' . $cell_style . 'text-align:right;">' . $user->id . '</td>';
                $html .= '<td style="' . $cell_style . '">' . e($user->name ?: '--') . '</td>';
                $html .= '<td style="' . $cell_style . '">' . e($user->username ?: '--') . '</td>';
                $html .= '<td style="' . $cell_style . '">' . e($user->email ?: '--') . '</td>';
                $html .= '<td style="' . $cell_style . '">' . e($user->access_level->name ?? '--') . '</td>';
                $html .= '<td style="' . $cell_style . 'text-align:center;">' . ($user->active ? 'Sim' : 'Não') . '</td>';
                $html .= '<td style="' . $cell_style . 'text-align:right;">'
                    . ($user->created_at ? $user->created_at->format('d/m/Y H:i') : '--') . '</td>';
                $html .= '</tr>';
            }
        } else {
            $html .= '<tr><td colspan="' . count($columns) . '" style="' . $cell_style
                . 'text-align:center;">Não há registros</td></tr>';
        }

        $html .= '</table>';

        return $html;
    }
}
