<?php

namespace App\Modules\Phones\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Phones\Http\Requests\PhoneRequest;
use App\Modules\Phones\PhoneService;
use Illuminate\Http\Request;

class PhoneController extends Controller
{
    public function __construct(PhoneService $phone_service)
    {
        // $this->authorizeResource("App\Modules\Phones\Phone", "App\Modules\Phones\Phone");
        $this->phone_service = $phone_service;
    }

    public function store(PhoneRequest $request)
    {
        return response()->json([
            'error' => false,
            'message' => __('phones::toasts.store'),
            'phone' => $this->phone_service->store($request->toArray()),
        ]);
    }

    public function update(PhoneRequest $request, $id)
    {
        return response()->json([
            'error' => false,
            'message' => __('phones::toasts.update'),
            'phone' => $this->phone_service->update($request->toArray(), $id),
        ]);
    }

    public function destroy($id)
    {
        $this->phone_service->destroy($id);

        return response()->json([
            'error' => false,
            'message' => __('phones::toasts.destroy'),
        ]);
    }

    public function restore($id)
    {
        $this->phone_service->restore($id);

        return response()->json([
            'error' => false,
            'message' => __('phones::toasts.restore'),
        ]);
    }

    public function get(Request $request)
    {
        return response()->json([
            'error' => false,
            'phones' => $this->phone_service->api->get($request->toArray()),
        ]);
    }

    public function find(Request $request)
    {
        return response()->json([
            'error' => false,
            'phone' => $this->phone_service->api->find($request->toArray()),
        ]);
    }

    public function paginate(Request $request)
    {
        return response()->json(
            $this->phone_service->api->paginate($request->toArray())
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
