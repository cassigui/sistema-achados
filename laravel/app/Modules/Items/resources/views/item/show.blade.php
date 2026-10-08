<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }} | Detalhes do Item</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
                <i class="fa-solid fa-box-open me-2"></i>Achados e Perdidos UTFPR
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3 text-white">
                    <span class="small"><i class="fa-solid fa-user me-1"></i>
                        {{ Auth::user()->name ?? 'Usuário' }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm">Sair</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="container py-2">
        <div class="row justify-content-center">
            <div class="col-lg-9">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 font-weight-bold text-dark mb-1">Detalhes do Item #{{ $item->id }}</h1>
                        <p class="text-muted mb-0">Informações detalhadas sobre o item cadastrado.</p>
                    </div>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Voltar ao Painel
                    </a>
                </div>

                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-check-circle me-1"></i> {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="row g-4">

                            <div class="col-md-5 text-center">
                                @if ($item->image_path)
                                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}"
                                        class="img-fluid rounded border shadow-sm w-100"
                                        style="max-height: 350px; object-fit: cover;">
                                @else
                                    <div class="bg-light text-secondary rounded d-flex flex-column align-items-center justify-content-center border p-5"
                                        style="min-height: 250px;">
                                        <i class="fas fa-image fa-4x mb-3 text-black-50"></i>
                                        <span class="text-muted fw-semibold">Sem foto cadastrada</span>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-7 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        @if ($item->status === 'devolvido')
                                            <span class="badge bg-success fs-6"><i class="fas fa-check-circle me-1"></i>
                                                Devolvido</span>
                                        @elseif($item->status === 'encontrado')
                                            <span class="badge bg-info text-dark fs-6"><i
                                                    class="fas fa-search me-1"></i> Encontrado</span>
                                        @else
                                            <span class="badge bg-warning text-dark fs-6"><i
                                                    class="fas fa-exclamation-triangle me-1"></i> Perdido</span>
                                        @endif

                                        <span class="badge bg-light text-dark border fs-6">
                                            <i class="fas fa-tag me-1 text-muted"></i>
                                            {{ ucfirst($item->category->name ?? $item->category) }}
                                        </span>
                                    </div>

                                    <h2 class="h3 font-weight-bold text-dark mt-3 mb-3">{{ $item->title }}</h2>

                                    <div class="mb-4">
                                        <h6 class="text-muted fw-bold text-uppercase small">Descrição</h6>
                                        <p class="text-secondary fs-6 lead" style="white-space: pre-line;">
                                            {{ $item->description }}</p>
                                    </div>
                                </div>

                                <div class="border-top pt-3 mt-3">
                                    <div class="row text-muted small">
                                        <div class="col-6">
                                            <i class="fas fa-user me-1 text-primary"></i> <strong>Registrado
                                                por:</strong><br>
                                            <span
                                                class="text-dark">{{ $item->user->name ?? 'Usuário desconhecido' }}</span>
                                        </div>
                                        <div class="col-6">
                                            <i class="fas fa-calendar-alt me-1 text-primary"></i> <strong>Data de
                                                Registro:</strong><br>
                                            <span
                                                class="text-dark">{{ $item->created_at ? $item->created_at->format('d/m/Y \à\s H:i') : '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    @if (Auth::check() &&
                            ((int) Auth::id() === (int) $item->user_id || Auth::user()->isAdmin() || Auth::user()->super_admin))
                        <div
                            class="card-footer bg-light p-3 d-flex flex-wrap align-items-center justify-content-between gap-2">

                            <div>
                                @if ($item->status !== 'devolvido')
                                    <form action="{{ route('items.status.update', $item->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="devolvido">
                                        <button type="submit" class="btn btn-success"
                                            onclick="return confirm('Deseja marcar este item como devolvido?');">
                                            <i class="fas fa-check-circle me-1"></i> Marcar como Devolvido
                                        </button>
                                    </form>
                                @else
                                    <span class="badge bg-success-subtle text-success border border-success p-2">
                                        <i class="fas fa-check-circle me-1"></i> Este item já foi finalizado como
                                        devolvido.
                                    </span>
                                @endif
                            </div>

                            <div class="d-flex gap-2">
                                @if (Route::has('items.edit'))
                                    <a href="{{ route('items.edit', $item->id) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-edit me-1"></i> Editar Item
                                    </a>
                                @endif

                                @if (Route::has('items.destroy'))
                                    <form action="{{ route('items.destroy', $item->id) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Tem certeza que deseja excluir este item?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="fas fa-trash me-1"></i> Excluir Item
                                        </button>
                                    </form>
                                @endif
                            </div>

                        </div>
                    @endif
                </div>

                <div class="card border-0 shadow-sm mb-5">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <h5 class="card-title fw-bold text-dark mb-0">
                            <i class="fas fa-comments text-primary me-2"></i> Mensagens e Comentários
                            ({{ $item->comments->count() }})
                        </h5>
                    </div>
                    <div class="card-body p-4 pt-0">

                        <form action="{{ route('comments.store', $item->id) }}" method="POST" class="mb-4">
                            @csrf
                            <div class="mb-3">
                                <label for="content" class="form-label fw-semibold text-muted small">Escreva uma
                                    mensagem sobre este item:</label>
                                <textarea name="content" id="content" rows="3" class="form-control @error('content') is-invalid @enderror"
                                    placeholder="Ex: Encontrei este item na biblioteca..." required></textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-1"></i> Enviar Comentário
                                </button>
                            </div>
                        </form>

                        <hr class="text-muted opacity-25 my-4">

                        <div class="comments-list">
                            @forelse($item->comments as $comment)
                                <div
                                    class="d-flex gap-3 mb-3 p-3 bg-light rounded border-start border-4 border-primary">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="mb-0 fw-bold text-dark">{{ $comment->user->name ?? 'Usuário' }}
                                            </h6>
                                            <small
                                                class="text-muted">{{ $comment->created_at->format('d/m/Y \à\s H:i') }}</small>
                                        </div>
                                        <p class="text-secondary mb-0" style="white-space: pre-line;">
                                            {{ $comment->content }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-muted">
                                    <i class="fas fa-comment-slash fa-2x mb-2 text-black-50"></i>
                                    <p class="mb-0 small">Nenhum comentário enviado ainda. Seja o primeiro a comentar!
                                    </p>
                                </div>
                            @endforelse
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
