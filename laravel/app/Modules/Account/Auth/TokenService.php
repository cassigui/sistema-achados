<?php

namespace App\Modules\Account\Auth;

use App\Modules\Account\AccountException;
use App\Modules\Account\Users\User;
// use App\Modules\Auditings\AuditingService;
use Tymon\JWTAuth\Facades\JWTAuth;

class TokenService
{
    /** Validade do token temporário entre a senha e o código MFA. */
    const MFA_TOKEN_MINUTES = 5;

    public function __construct(User $user, MfaService $mfa_service)
    {
        $this->model       = $user;
        $this->mfa_service = $mfa_service;
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
     * Segundo passo do login: valida o código (app ou recuperação) e emite o
     * token definitivo.
     */
    public function validateMfaLogin(string $temp_token, string $code, array $relations = [], bool $is_backup_code = false)
    {
        JWTAuth::setToken($temp_token);

        try {
            $user = JWTAuth::authenticate();
        } catch (\Exception $e) {
            throw new AccountException(401, 'Sessão expirada. Faça o login novamente.');
        }

        if (empty($user)) {
            throw new AccountException(401, 'Sessão expirada. Faça o login novamente.');
        }

        $valid = $is_backup_code
        ? $this->mfa_service->verifyBackupCode($user, $code)
        : $this->mfa_service->verifyCode($user, $code);

        if (!$valid) {
            throw new AccountException(422, 'Código de verificação incorreto.');
        }

        // Decodificar o token temporário deixou mfa_pending acumulada na
        // factory; sem limpar, o token definitivo nasceria com a claim.
        $this->resetJwtClaims();

        $token = auth('api')->login($user);

        $user->makeVisible('super_admin');

        $this->load($user, $relations);

        return compact('token', 'user');
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
                throw new AccountException(401, __('wf.account::toasts.users.wrong_credentials'));
            }
            $user = $this->model->where('username', $credentials['username'])->where('active', 1)->firstOrFail();

            if ($user->google2fa_enabled) {
                return $this->mfaChallenge($user);
            }

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
                throw new AccountException(401, __('wf.account::toasts.users.wrong_credentials'));
            }

            $user = $this->model->where('username', $credentials['username'])->firstOrFail();

            if ($user->google2fa_enabled) {
                return $this->mfaChallenge($user);
            }

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
                throw new AccountException(401, __('wf.account::toasts.users.wrong_credentials'));
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
            throw new AccountException(401, __('wf.account::toasts.users.wrong_credentials'));
        }

        $user = auth('api')->setToken($token)->user();

        if (!empty($user)) {

            if ($make_visible) {
                $user->makeVisible('super_admin');
            }

            $this->load($user, $relations);
        } else {
            throw new AccountException(401, __('wf.account::toasts.users.wrong_credentials'));
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
