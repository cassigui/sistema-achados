<?php

namespace App\Modules\Account\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Account\AccountException;
use App\Modules\Account\Auth\MfaService;
use App\Modules\Account\Auth\TokenService;
use Auth;
use Illuminate\Http\Request;

/**
 * MFA do próprio usuário: como o perfil, o usuário sai sempre do token.
 *
 * As falhas de validação (senha errada, código inválido) voltam como 422 e não
 * 401 — no frontend o 401 derruba a sessão (HelperService.responseErrors).
 */
class MfaController extends Controller
{
    protected $mfa_service;
    protected $token_service;

    public function __construct(MfaService $mfa_service, TokenService $token_service)
    {
        $this->mfa_service   = $mfa_service;
        $this->token_service = $token_service;
    }

    /** Gera (ou reaproveita) o secret e devolve a URL otpauth pro QR Code. */
    public function setup()
    {
        try {
            $user = Auth::user();

            $secret = $this->mfa_service->generateSecret($user);

            return response()->json([
                'error'       => false,
                'secret'      => $secret,
                'qr_code_url' => $this->mfa_service->getQrCodeUrl($user),
            ]);
        } catch (AccountException $e) {
            return $this->fail($e->getMessage());
        }
    }

    public function enable(Request $request)
    {
        $request->validate(['code' => 'required|string|size:6']);

        try {
            $backup_codes = $this->mfa_service->enableMfa(Auth::user(), $request->code);

            return response()->json([
                'error'        => false,
                'message'      => 'MFA ativada com sucesso.',
                'backup_codes' => $backup_codes,
            ]);
        } catch (AccountException $e) {
            return $this->fail($e->getMessage());
        }
    }

    public function disable(Request $request)
    {
        $request->validate(['password' => 'required|string']);

        try {
            $this->mfa_service->disableMfa(Auth::user(), $request->password);

            return response()->json([
                'error'   => false,
                'message' => 'MFA desativada com sucesso.',
            ]);
        } catch (AccountException $e) {
            return $this->fail($e->getMessage());
        }
    }

    public function regenerateBackupCodes(Request $request)
    {
        $request->validate(['password' => 'required|string']);

        try {
            $backup_codes = $this->mfa_service->regenerateBackupCodes(Auth::user(), $request->password);

            return response()->json([
                'error'        => false,
                'message'      => 'Novos códigos de recuperação gerados.',
                'backup_codes' => $backup_codes,
            ]);
        } catch (AccountException $e) {
            return $this->fail($e->getMessage());
        }
    }

    /**
     * Segundo passo do login: troca o token temporário (claim mfa_pending) pelo
     * token definitivo depois de validar o código do app ou de recuperação.
     */
    public function validateLogin(Request $request)
    {
        $request->validate([
            'temp_token'     => 'required|string',
            'code'           => 'required|string',
            'is_backup_code' => 'sometimes|boolean',
        ]);

        try {
            $result = $this->token_service->validateMfaLogin(
                $request->temp_token,
                $request->code,
                $request->relations ?? [],
                filter_var($request->input('is_backup_code', false), FILTER_VALIDATE_BOOLEAN)
            );

            return response()->json(array_merge(['error' => false], $result));
        } catch (AccountException $e) {
            return $this->fail($e->getMessage());
        }
    }

    private function fail(string $message)
    {
        return response()->json([
            'error'   => true,
            'message' => $message,
        ], 422);
    }
}
