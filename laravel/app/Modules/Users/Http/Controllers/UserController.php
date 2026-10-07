<?php

namespace App\Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Users\Http\Requests\UserRequest;
use App\Modules\Users\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(UserService $user_service)
    {
        // $this->authorizeResource("App\Modules\Users\User", "App\Modules\Users\User");
        $this->user_service = $user_service;
    }

    public function store(UserRequest $request)
    {
        return response()->json([
            'error' => false,
            'message' => __('wf.users::toasts.store'),
            'user' => $this->user_service->store($request->toArray()),
        ]);
    }

    public function update(UserRequest $request, $id)
    {
        return response()->json([
            'error' => false,
            'message' => __('wf.users::toasts.update'),
            'user' => $this->user_service->update($request->toArray(), $id),
        ]);
    }

    public function destroy($id)
    {
        $this->user_service->destroy($id);

        return response()->json([
            'error' => false,
            'message' => __('wf.users::toasts.destroy'),
        ]);
    }

    public function restore($id)
    {
        $this->user_service->restore($id);

        return response()->json([
            'error' => false,
            'message' => __('wf.users::toasts.restore'),
        ]);
    }

    public function get(Request $request)
    {
        return response()->json([
            'error' => false,
            'users' => $this->user_service->api->get($request->toArray()),
        ]);
    }

    public function find(Request $request)
    {
        return response()->json([
            'error' => false,
            'user' => $this->user_service->api->find($request->toArray()),
        ]);
    }

    public function paginate(Request $request)
    {
        return response()->json(
            $this->user_service->api->paginate($request->toArray())
        );
    }

    protected function resourceAbilityMap()
    {
        return array_merge(parent::resourceAbilityMap(), [
            'ngTableGet' => 'view',
            'restore' => 'restore',
        ]);
    }
}
