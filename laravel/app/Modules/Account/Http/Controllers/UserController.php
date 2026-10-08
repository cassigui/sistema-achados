<?php
namespace App\Modules\Account\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Account\AccountException as AppAccountException;
use App\Modules\Account\Auth\AuthService;
use App\Modules\Account\Auth\TokenService;
use App\Modules\Account\Http\Requests\CheckResetTokenRequest;
use App\Modules\Account\Http\Requests\ForgotPasswordRequest;
use App\Modules\Account\Http\Requests\LogInRequest;
use App\Modules\Account\Http\Requests\StoreUserRequest;
use App\Modules\Account\Http\Requests\UpdatePasswordRequest;
use App\Modules\Account\Http\Requests\UpdateUserRequest;
use App\Modules\Account\Users\UserService;
use App\Modules\Base\Utilities\UtilityService;
use App\Modules\Images\ImageService;
use Auth;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Reader\Html;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UserController extends Controller
{

    public function __construct(
        UserService $user_service,
        AuthService $auth_service,
        UtilityService $utility_service,
        TokenService $token_service,
        ImageService $image_service
    ) {
        // $this->authorizeResource("App\Modules\Account\Users\User", "App\Modules\Account\Users\User");
        $this->user_service    = $user_service;
        $this->auth_service    = $auth_service;
        $this->token_service   = $token_service;
        $this->utility_service = $utility_service;
        $this->image_service   = $image_service;
    }

    protected function resourceAbilityMap()
    {
        return array_merge(parent::resourceAbilityMap(), [
            'find'     => 'view',
            'get'      => 'view',
            'paginate' => 'view',
            'restore'  => 'restore',
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->user_service->store(
            $request->except('relations'),
            $request->only('relations')
        );

        return response()->json([
            'error'   => false,
            'user'    => $user,
            'message' => __('account::toasts.users.store'),
        ]);
    }

    public function loginPage()
    {
        return view('account::auth.login.login_page');
    }
    public function registerPage()
    {
        return view('account::auth.register.register_page');
    }

    public function login(LogInRequest $request)
    {
        $credentials = $request->validated();

        $attemptData = array_merge($credentials, ['active' => 1]);

        if (Auth::attempt($attemptData)) {
            $request->session()->regenerate();

            if ($request->wantsJson()) {
                return response()->json([
                    'error'   => false,
                    'message' => 'Login realizado com sucesso!',
                ]);
            }

            return redirect()->intended(route('dashboard'))->with('status', 'Bem-vindo de volta!');
        }

        return back()->withErrors([
            'email' => 'As credenciais informadas estão incorretas ou a conta está inativa.',
        ])->onlyInput('email');
    }

    public function register(StoreUserRequest $request)
    {
        $data = array_merge($request->validated(), [
            'username'        => strstr($request->email, '@', true),
            'access_level_id' => 2,
            'active'          => true,
        ]);

        $user = $this->user_service->store($data);
        Auth::login($user);

        if ($request->wantsJson()) {
            return response()->json([
                'error'   => false,
                'user'    => $user,
                'message' => __('account::toasts.users.store'),
            ]);
        }

        return redirect()->route('dashboard')->with('status', 'Conta criada com sucesso!');
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = $this->user_service->update(
            $request->except('relations'),
            $id,
            $request->only('relations')
        );

        return response()->json([
            'error'   => false,
            'user'    => $user,
            'message' => __('account::toasts.users.update'),
        ]);
    }

    public function destroy($id)
    {
        $this->user_service->destroy($id);

        return response()->json([
            'error'   => false,
            'message' => __('account::toasts.users.destroy'),
        ]);
    }

    public function restore($id)
    {
        $this->user_service->restore($id);

        return response()->json([
            'error'   => false,
            'message' => __('account::toasts.users.restore'),
        ]);
    }

    public function authenticate(LogInRequest $request)
    {
        if ($this->auth_service->authenticate($request->toArray())) {
            // return redirect('sistema');
            return response()->json(
                [
                    'error'   => false,
                    'message' => 'Autenticado com sucesso',
                ], 200);
        }

        return response()->json(
            [
                'error'   => true,
                'message' => __('account::toasts.users.wrong_credentials'),
            ], 200);

        // return redirect('login')->withInput()->with('status', ['error', ]);

    }

    public function find(Request $request)
    {
        return response()->json([
            'error' => false,
            'user'  => $this->user_service->api->find($request->toArray()),
        ]);
    }

    public function get(Request $request)
    {
        return response()->json([
            'error' => false,
            'users' => $this->user_service->api->get($request->toArray()),
        ]);
    }

    public function paginate(Request $request)
    {
        $req = $request->toArray();

        return response()->json(
            $this->user_service->api->paginate($req)
        );
    }

    public function export(Request $request)
    {
        $html = $this->user_service->export($request->toArray());

        libxml_use_internal_errors(true);

        $reader      = new Html();
        $spreadsheet = $reader->loadFromString($html);

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Usuários');

        $file_name = 'Usuários';

        // 1. Auto size em todas as colunas
        $highestColumnIndex = Coordinate::columnIndexFromString($sheet->getHighestColumn());
        for ($col = 1; $col <= $highestColumnIndex; $col++) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($col))->setAutoSize(true);
        }

        // 2. Alinhamento e quebra de texto
        $dimension = $sheet->calculateWorksheetDimension();
        $sheet->getStyle($dimension)->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
        $sheet->getStyle($dimension)->getAlignment()->setWrapText(true);

        // 3. Congelar cabeçalho
        $sheet->freezePane('A2');

        // 4. Filtro automático
        $sheet->setAutoFilter($dimension);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $file_name . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function checkEmailAvailability(Request $request)
    {
        return response()->json([
            'error'  => false,
            'exists' => $this->utility_service->exists('users', 'email', $request->email),
        ]);
    }

    public function sendResetLinkEmail(ForgotPasswordRequest $request)
    {
        $this->user_service->sendResetLinkEmail($request->toArray());

        return response()->json([
            'error'   => false,
            'message' => __('account::toasts.password_reset_email_sent'),
        ]);
    }

    public function validateResetToken(CheckResetTokenRequest $request)
    {

        if (! $this->user_service->validateResetToken($request->toArray())) {
            throw new AppAccountException(400, 'Invalid e-mail or code.');
        }

        return response()->json([
            'error' => false,
            'valid' => true,
        ]);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $relations = $request->relations ?? [];

        $this->user_service->updatePassword(
            $request->email,
            $request->token,
            $request->password
        );

        $result = $this->token_service->authenticateByEmail([
            'email'    => $request->email,
            'password' => $request->password,
        ], $relations, true);

        return response()->json([
            'error'   => false,
            'message' => __('account::toasts.update-password'),
            'user'    => $result['user'],
            'pcrypt'  => encrypt($request->email),
            'token'   => $result['token'],
        ]);
    }
}
