<?php

namespace App\Modules\Account\Auth;

use App\Modules\Account\AccountException;
use App\Modules\Account\Users\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FA\Google2FA;

/**
 * MFA por TOTP (Google Authenticator e compatíveis) + códigos de recuperação.
 * O secret e os códigos ficam ocultos no model — a API só os devolve no
 * momento da geração.
 */
class MfaService
{
    /** Quantidade de códigos de recuperação gerados por vez. */
    const BACKUP_CODES_COUNT = 10;

    protected $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Devolve o secret do usuário, reaproveitando um setup em andamento
     * (secret já gerado mas MFA ainda não confirmada).
     */
    public function generateSecret(User $user): string
    {
        if (!empty($user->google2fa_secret) && !$user->google2fa_enabled) {
            return $user->google2fa_secret;
        }

        $secret = $this->google2fa->generateSecretKey();

        $user->google2fa_secret = $secret;
        $user->save();

        return $secret;
    }

    /** URL otpauth:// que o frontend transforma em QR Code. */
    public function getQrCodeUrl(User $user): string
    {
        if (empty($user->google2fa_secret)) {
            throw new AccountException(400, 'O usuário não iniciou a configuração da MFA.');
        }

        return $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email ?? $user->username,
            $user->google2fa_secret
        );
    }

    public function verifyCode(User $user, string $code): bool
    {
        if (empty($user->google2fa_secret)) {
            return false;
        }

        return (bool) $this->google2fa->verifyKey($user->google2fa_secret, $code);
    }

    /**
     * Confirma o setup com um código válido e devolve os códigos de recuperação.
     */
    public function enableMfa(User $user, string $code): array
    {
        if (!$this->verifyCode($user, $code)) {
            throw new AccountException(400, 'Código de verificação inválido.');
        }

        try {
            DB::beginTransaction();

            $backup_codes = $this->generateBackupCodes();

            $user->google2fa_enabled = true;
            $user->backup_codes      = $backup_codes;
            $user->save();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }

        return $backup_codes;
    }

    public function disableMfa(User $user, string $password): bool
    {
        $this->assertPassword($user, $password);

        $user->google2fa_enabled = false;
        $user->google2fa_secret  = null;
        $user->backup_codes      = null;

        return $user->save();
    }

    public function regenerateBackupCodes(User $user, string $password): array
    {
        $this->assertPassword($user, $password);

        if (!$user->google2fa_enabled) {
            throw new AccountException(400, 'A MFA não está ativa.');
        }

        $backup_codes = $this->generateBackupCodes();

        $user->backup_codes = $backup_codes;
        $user->save();

        return $backup_codes;
    }

    /** Consome um código de recuperação (uso único). */
    public function verifyBackupCode(User $user, string $code): bool
    {
        $codes = $user->backup_codes;

        if (is_string($codes)) {
            $codes = json_decode($codes, true);
        }

        if (empty($codes) || !is_array($codes)) {
            return false;
        }

        $key = array_search($code, $codes, true);

        if ($key === false) {
            return false;
        }

        unset($codes[$key]);
        $user->backup_codes = array_values($codes);

        return $user->save();
    }

    private function assertPassword(User $user, string $password): void
    {
        if (!Hash::check($password, $user->password)) {
            throw new AccountException(401, 'Senha incorreta.');
        }
    }

    private function generateBackupCodes(): array
    {
        $codes = [];

        for ($i = 0; $i < self::BACKUP_CODES_COUNT; $i++) {
            $codes[] = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        }

        return $codes;
    }
}
