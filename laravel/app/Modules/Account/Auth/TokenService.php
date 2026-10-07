<?php

namespace App\Modules\Account\Auth;

use App\Modules\Account\AccountException;
use App\Modules\Account\Users\User;
// use App\Modules\Auditings\AuditingService;
use Tymon\JWTAuth\Facades\JWTAuth;

class TokenService
{
    public function __construct(User $user)
    {
        $this->model       = $user;
        // $this->auditing_service = $auditing_service;
    }

    /**
     * Token curto com a claim mfa_pending — só serve pro segundo passo do login.
     */
    private function mfaChallenge(User $user): array
    {
        $temp_token = JWTAuth::claims([
            'mfa_pending' => true,
            'exp'         => now()->addMinutes(self::MFA_TOKEN_MINUTES)->timestamp,
        ])->fromUser($user);

        $this->resetJwtClaims();

        return [
            'mfa_required' => true,
            'temp_token'   => $temp_token,
        ];
    }

    /**
     * O JWTAuth e o PayloadFactory são singletons e acumulam custom claims —
     * inclusive as decodificadas. Sem limpar, a claim mfa_pending vaza pros
     * próximos tokens emitidos no mesmo processo.
     */
    private function resetJwtClaims(): void
    {
        JWTAuth::customClaims([]);
        JWTAuth::factory()->emptyClaims();
    }

    /**
     * Realiza a autentiação e retorna usuário e token
     *
     * @var array $credentials
     * ['username', 'password']
     * @return bool
     */
    public function authenticate(array $credentials, array $relations = [], bool $make_visible = false)
    {
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                throw new AccountException(401, __('account::toasts.users.wrong_credentials'));
            }
            $user = $this->model->where('username', $credentials['username'])->where('active', 1)->firstOrFail();

            if ($make_visible) {
                $user->makeVisible('super_admin');
            }

            $this->load($user, $relations);

        } catch (\Exception $e) {
            throw $e;
        }

        return compact('token', 'user');
    }

    public function validateLogin(array $credentials, array $relations = [], bool $make_visible = false)
    {
        try {
            if (!$token = auth('api')->attempt($credentials)) {
                throw new AccountException(401, __('account::toasts.users.wrong_credentials'));
            }

            $user = $this->model->where('username', $credentials['username'])->firstOrFail();

            if ($make_visible) {
                $user->makeVisible('super_admin');
            }

            $this->load($user, $relations);
        } catch (\Exception $e) {
            throw $e;
        }

        return compact('token', 'user');
    }

    /**
     * Realiza a autentiação por e-mail e retorna usuário e token
     *
     * @var array $credentials
     * ['email', 'password']
     * @return bool
     */
    public function authenticateByEmail(array $credentials, array $relations = [], bool $make_visible = false)
    {
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                throw new AccountException(401, __('account::toasts.users.wrong_credentials'));
            }

            $user = $this->model->where('email', $credentials['email'])->firstOrFail();

            if ($make_visible) {
                $user->makeVisible('super_admin');
            }

            // $this->auditingAction('login', $user);

            $this->load($user, $relations);
        } catch (\Exception $e) {
            throw $e;
        }

        return compact('token', 'user');
    }

    /**
     * Valida o token enviado e retorna as informações do usuário
     *
     * @var string $token Token JWT
     *
     * @var array $relations Relações do eloquent
     * para serem retornadas junto ao modelo
     *
     * @var bool $make_visible Retornar ou não informações para administradores
     *
     * @return \App\Modules\Account\Users\User
     */
    public function validateToken(string $token = '', array $relations = [], bool $make_visible = false)
    {
        if ($token == 'null') {
            throw new AccountException(401, __('account::toasts.users.wrong_credentials'));
        }

        $user = auth('api')->setToken($token)->user();

        if (!empty($user)) {

            if ($make_visible) {
                $user->makeVisible('super_admin');
            }

            $this->load($user, $relations);
        } else {
            throw new AccountException(401, __('account::toasts.users.wrong_credentials'));
        }

        // $this->auditingAction('login', $user);

        return $user;
    }

    private function load(User &$user, array $relations = [])
    {
        if (!empty($relations)) {
            try {
                $user = call_user_func_array([$user, 'load'], $relations);
            } catch (RelationNotFoundException $e) {
                $message = $e->getMessage();
                \Log::warning("Tentou carregar relação não existente [$message]");
            }
        }
    }
}
