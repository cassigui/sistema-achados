<?php
namespace App\Modules\Items\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Items\Http\Requests\ItemRequest;
use App\Modules\Items\Item;
use App\Modules\Items\ItemService;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function __construct(ItemService $item_service)
    {
        // $this->authorizeResource("App\Modules\Items\Item", "App\Modules\Items\Item");
        $this->item_service = $item_service;
    }

    public function dashboard(Request $request)
    {
        $query = Item::with(['user']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $items = $query->latest()->paginate(10);

        return view('items::item.dashboard', compact('items'));
    }

    public function create()
    {
        return view('items::item.create');
    }

    public function store(ItemRequest $request)
    {
        return response()->json([
            'error'   => false,
            'message' => __('items::toasts.store'),
            'item'    => $this->item_service->store($request->toArray()),
        ]);
    }

    public function update(ItemRequest $request, $id)
    {
        return response()->json([
            'error'   => false,
            'message' => __('items::toasts.update'),
            'item'    => $this->item_service->update($request->toArray(), $id),
        ]);
    }

    public function destroy($id)
    {
        $this->item_service->destroy($id);

        return response()->json([
            'error'   => false,
            'message' => __('items::toasts.destroy'),
        ]);
    }

    public function restore($id)
    {
        $this->item_service->restore($id);

        return response()->json([
            'error'   => false,
            'message' => __('items::toasts.restore'),
        ]);
    }

    public function get(Request $request)
    {
        return response()->json([
            'error' => false,
            'items' => $this->item_service->api->get($request->toArray()),
        ]);
    }

    public function find(Request $request)
    {
        return response()->json([
            'error' => false,
            'item'  => $this->item_service->api->find($request->toArray()),
        ]);
    }

    public function paginate(Request $request)
    {
        return response()->json(
            $this->item_service->api->paginate($request->toArray())
        );
    }

    protected function resourceAbilityMap()
    {
        return array_merge(parent::resourceAbilityMap(), [
            'ngTableGet' => 'view',
            'restore'    => 'restore',
        ]);
    }
}
