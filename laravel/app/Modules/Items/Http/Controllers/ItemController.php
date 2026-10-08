<?php
namespace App\Modules\Items\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Items\Http\Requests\ItemRequest;
use App\Modules\Items\Item;
use App\Modules\Items\ItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function __construct(ItemService $item_service)
    {
        // $this->authorizeResource("App\Modules\Items\Item", "App\Modules\Items\Item");
        $this->item_service = $item_service;
    }

    public function dashboard(Request $request)
    {
        $userId = Auth::id();

        $query = Item::with(['user']);

        $query->where(function ($q) use ($userId) {
            $q->where('status', '!=', 'devolvido')
                ->orWhere('user_id', $userId);
        });

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category', $request->input('category_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
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
        $data            = $request->validated();
        $data['user_id'] = Auth::id();
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('items', 'public');
        }
        $item = $this->item_service->store($data);

        return redirect()->route('dashboard')->with('status', 'Item registrado com sucesso!');
    }

    public function edit($id)
    {
        $item = Item::findOrFail($id);

        // Verifica permissão (autor ou admin)
        if (Auth::id() !== $item->user_id && ! Auth::user()->super_admin) {
            return redirect()->route('dashboard')->with('error', 'Ação não autorizada.');
        }

        return view('items::item.edit', compact('item'));
    }

    public function show($id)
    {
        $item = Item::with(['user'])->findOrFail($id);
        $item->load('comments');

        return view('items::item.show', compact('item'));
    }

    public function updateStatus(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        if ((int) Auth::id() !== (int) $item->user_id) {
            abort(403, 'Ação não autorizada.');
        }

        $request->validate([
            'status' => ['required', 'in:perdido,encontrado,devolvido'],
        ]);

        $item->update([
            'status' => $request->input('status'),
        ]);

        return redirect()->back()->with('status', 'Status do item atualizado com sucesso!');
    }

    public function update(ItemRequest $request, $id)
    {
        $item = Item::findOrFail($id);

        if (Auth::id() !== $item->user_id && ! Auth::user()->super_admin) {
            return redirect()->route('dashboard')->with('error', 'Ação não autorizada.');
        }

        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($item->image_path && Storage::disk('public')->exists($item->image_path)) {
                Storage::disk('public')->delete($item->image_path);
            }

            $data['image_path'] = $request->file('image')->store('items', 'public');
        }

        $item->update($data);

        return redirect()->route('dashboard')->with('status', 'Item atualizado com sucesso!');
    }

    public function destroy(Request $request, $id)
    {
        // Tenta recuperar o ID via $request->user() ou Auth
        $user   = $request->user() ?? auth()->user();
        $userId = $user ? $user->id : null;

        \Log::info('DELETE Executado - User via Request: ' . ($user ? $user->id : 'NULO'));

        if (! $userId) {
            return redirect()->route('dashboard')->with('error', 'Sessão não identificada.');
        }

        $item = Item::findOrFail($id);

        if ((int) $userId !== (int) $item->user_id) {
            return redirect()->route('dashboard')->with('error', 'Sem permissão para excluir este item.');
        }

        if ($item->image_path && Storage::disk('public')->exists($item->image_path)) {
            Storage::disk('public')->delete($item->image_path);
        }

        $item->delete();

        return redirect()->route('dashboard')->with('status', 'Item excluído com sucesso!');
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
