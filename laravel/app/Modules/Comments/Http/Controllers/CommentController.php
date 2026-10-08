<?php
namespace App\Modules\Comments\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Comments\Comment;
use App\Modules\Comments\CommentService;
use App\Modules\Comments\Http\Requests\CommentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct(CommentService $comment_service)
    {
        // $this->authorizeResource("App\Modules\Comments\Comment", "App\Modules\Comments\Comment");
        $this->comment_service = $comment_service;
    }

    public function store(CommentRequest $request, $item_id)
    {
        Comment::create([
            'item_id' => $item_id,
            'user_id' => Auth::id(),
            'content' => $request->input('content'),
        ]);

        return redirect()->back()->with('status', 'Comentário enviado com sucesso!');
    }
    public function update(CommentRequest $request, $id)
    {
        $comment = Comment::findOrFail($id);

        if (Auth::id() !== $comment->user_id && ! Auth::user()->super_admin) {
            return redirect()->route('dashboard')->with('error', 'Ação não autorizada.');
        }

        $data = $request->validated();
        $comment->update($data);

        return redirect()->route('dashboard')->with('status', 'Comment atualizado com sucesso!');
    }

    public function destroy(Request $request, $id)
    {
        $user   = $request->user() ?? auth()->user();
        $userId = $user ? $user->id : null;

        if (! $userId) {
            return redirect()->route('dashboard')->with('error', 'Sessão não identificada.');
        }

        $comment = Comment::findOrFail($id);

        if ((int) $userId !== (int) $comment->user_id) {
            return redirect()->route('dashboard')->with('error', 'Sem permissão para excluir este comentário.');
        }

        $comment->delete();

        return redirect()->route('dashboard')->with('status', 'Comentário excluído com sucesso!');
    }
    public function restore($id)
    {
        $this->comment_service->restore($id);

        return response()->json([
            'error'   => false,
            'message' => __('comments::toasts.restore'),
        ]);
    }

    public function get(Request $request)
    {
        return response()->json([
            'error'    => false,
            'comments' => $this->comment_service->api->get($request->toArray()),
        ]);
    }

    public function find(Request $request)
    {
        return response()->json([
            'error'   => false,
            'comment' => $this->comment_service->api->find($request->toArray()),
        ]);
    }

    public function paginate(Request $request)
    {
        return response()->json(
            $this->comment_service->api->paginate($request->toArray())
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
