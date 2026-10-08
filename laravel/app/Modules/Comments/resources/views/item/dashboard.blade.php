<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }} | Achados e Perdidos UTFPR</title>

    {{-- Bootstrap 5 e FontAwesome --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body class="bg-light">

    {{-- Navbar Superior --}}
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
                    <li class="nav-comment">
                        <a class="nav-link active" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                </ul>
                <div class="d-flex align-comments-center gap-3 text-white">
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

    {{-- Conteúdo Principal --}}
    <div class="container py-2">
        {{-- Header / Boas-vindas --}}
        <div class="d-flex justify-content-between align-comments-center mb-4">
            <div>
                <h1 class="h3 font-weight-bold text-dark mb-1">Achados e Perdidos</h1>
                <p class="text-muted mb-0">Gerencie os itens perdidos e encontrados no campus.</p>
            </div>
            @if (Route::has('comments.create'))
                <a href="{{ route('comments.create') }}" class="btn btn-primary shadow-sm">
                    <i class="fas fa-plus me-1"></i> Registrar Novo Comment
                </a>
            @endif
        </div>

        {{-- Alertas de feedback --}}
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-1"></i> {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Filtros e Busca --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('dashboard') }}" method="GET" class="row g-3">
                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control"
                            placeholder="Buscar por título ou descrição..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="category_id" class="form-select">
                            <option value="">Todas as Categorias</option>
                            <option value="eletronicos"
                                {{ request('category_id') == 'eletronicos' ? 'selected' : '' }}>Eletrônicos</option>
                            <option value="documentos" {{ request('category_id') == 'documentos' ? 'selected' : '' }}>
                                Documentos</option>
                            <option value="vestuario" {{ request('category_id') == 'vestuario' ? 'selected' : '' }}>
                                Vestuário</option>
                            <option value="outros" {{ request('category_id') == 'outros' ? 'selected' : '' }}>Outros
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">Todos os Status</option>
                            <option value="perdido" {{ request('status') == 'perdido' ? 'selected' : '' }}>Perdido
                            </option>
                            <option value="encontrado" {{ request('status') == 'encontrado' ? 'selected' : '' }}>
                                Encontrado</option>
                            <option value="devolvido" {{ request('status') == 'devolvido' ? 'selected' : '' }}>
                                Devolvido</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-outline-secondary">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabela do CRUD de Itens --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 80px;">Foto</th>
                                <th>Comment</th>
                                <th>Categoria</th>
                                <th>Status</th>
                                <th>Registrado por</th>
                                <th>Data/Hora</th>
                                <th class="text-end" style="width: 160px;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($comments as $comment)
                                <tr>
                                    <td>
                                        @if ($comment->image_path)
                                            <img src="{{ asset('storage/' . $comment->image_path) }}"
                                                alt="{{ $comment->title }}" class="rounded img-thumbnail"
                                                style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-light text-secondary rounded d-flex align-comments-center justify-content-center border"
                                                style="width: 50px; height: 50px;">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $comment->title }}</div>
                                        <small class="text-muted d-inline-block text-truncate"
                                            style="max-width: 250px;">
                                            {{ $comment->description }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ ucfirst($comment->category->name ?? $comment->category) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($comment->status === 'devolvido')
                                            <span class="badge bg-success">Devolvido</span>
                                        @elseif($comment->status === 'encontrado')
                                            <span class="badge bg-info text-dark">Encontrado</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Perdido</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="fw-semibold">{{ $comment->user->name ?? 'Usuário' }}</small>
                                    </td>
                                    <td>
                                        <small
                                            class="text-muted">{{ $comment->created_at ? $comment->created_at->format('d/m/Y H:i') : '-' }}</small>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm" role="group">
                                            @if (Route::has('comments.show'))
                                                <a href="{{ route('comments.show', $comment->id) }}"
                                                    class="btn btn-outline-info" title="Ver detalhes/comentários">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif

                                            @if ((int) Auth::id() === (int) $comment->user_id)
                                                @if (Route::has('comments.edit'))
                                                    <a href="{{ route('comments.edit', $comment->id) }}"
                                                        class="btn btn-outline-primary" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif

                                                @if (Route::has('comments.destroy'))
                                                    <form action="{{ route('comments.destroy', $comment->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Tem certeza que deseja excluir este comment?');">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                                            title="Excluir">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fas fa-box-open fa-3x mb-3 text-secondary"></i>
                                        <p class="mb-0">Nenhum comment registrado no sistema até o momento.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if (method_exists($comments, 'links'))
                <div class="card-footer bg-white border-0 py-3">
                    {{ $comments->links() }}
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
