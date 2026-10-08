<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }} | Notificações de Reivindicação</title>

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
                        <a class="nav-link active" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                </ul>

                <!-- Grupo direito: Notificações, Nome do Usuário e Botão Sair -->
                <div class="d-flex align-items-center gap-3 text-white">

                    @if (auth()->check() && (auth()->user()->isAdmin() || auth()->user()->super_admin))
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link position-relative px-2 py-1 text-white rounded hover-bg"
                                    href="{{ route('admin.notifications') }}" title="Notificações de Reivindicação">
                                    <i class="fas fa-bell fa-lg"></i>
                                    @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                                    @if ($unreadCount > 0)
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light">
                                            {{ $unreadCount }}
                                            <span class="visually-hidden">notificações não lidas</span>
                                        </span>
                                    @endif
                                </a>
                            </li>
                        </ul>
                        <div class="vr bg-light opacity-50 d-none d-lg-block" style="height: 24px;"></div>
                    @endif

                    <span class="small">
                        <i class="fa-solid fa-user me-1"></i> {{ Auth::user()->name ?? 'Usuário' }}
                    </span>

                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm">Sair</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-bell me-2 text-primary"></i> Notificações de Reivindicação</h2>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Voltar
                    </a>
                </div>

                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($notifications as $notification)
                                @php
                                    $data = is_string($notification->data)
                                        ? json_decode($notification->data, true)
                                        : $notification->data;
                                @endphp

                                <div
                                    class="list-group-item d-flex justify-content-between align-items-center p-3 {{ $notification->read_at ? 'bg-light text-muted' : '' }}">
                                    <div>
                                        <p class="mb-1 fw-medium text-dark">
                                            {{ $data['message'] ?? 'Nova notificação do sistema.' }}
                                        </p>
                                        <small class="text-muted">
                                            <i class="far fa-clock me-1"></i>
                                            {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                        </small>
                                    </div>

                                    <div class="d-flex align-items-center gap-2">
                                        @if (!$notification->read_at)
                                            <form action="{{ route('admin.notifications.read', $notification->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-primary"
                                                    title="Marcar como lida">
                                                    <i class="fas fa-check"></i> Marcar como lida
                                                </button>
                                            </form>
                                        @else
                                            <span class="badge bg-secondary">Lida</span>
                                        @endif

                                        @if (isset($data['item_id']))
                                            <a href="{{ route('items.show', $data['item_id']) }}"
                                                class="btn btn-sm btn-outline-info" title="Ver Item">
                                                <i class="fas fa-eye"></i> Ver Item
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="p-5 text-center text-muted">
                                    <i class="fas fa-bell-slash fa-3x mb-3 text-secondary"></i>
                                    <p class="mb-0">Não há notificações no momento.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                @if (method_exists($notifications, 'links') && $notifications->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
