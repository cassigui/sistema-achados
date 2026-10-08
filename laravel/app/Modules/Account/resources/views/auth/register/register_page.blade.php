<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }} | Criar Conta</title>

    <link rel="stylesheet" href="{{ asset('assets/css/auth.min.css?v=1.0') }}" />

    <!-- CSS / Ícones (Bootstrap & FontAwesome) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
</head>

<body>

    <div class="container py-5">
        <div class="container_login_form">
            <div class="row row_login justify-content-center align-items-center">

                <div class="col-lg-6 col-md-8">

                    <div class="container_auth_header mb-4">
                        <div class="container_icon mb-2">
                            <i class="fa-solid fa-user-plus fa-2x text-primary"></i>
                        </div>
                        <h1 class="title_login h3 font-weight-bold">Criar Nova Conta</h1>
                        <p class="text_login text-muted">
                            Preencha os dados abaixo para se cadastrar na plataforma.
                        </p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.submit') }}" id="register-form">
                        @csrf

                        <!-- Nome -->
                        <div class="form-group mb-3">
                            <label for="name" class="form-label font-weight-bold">Nome Completo</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input id="name" type="text" name="name" value="{{ old('name') }}"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Seu nome completo" required autofocus />
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- E-mail -->
                        <div class="form-group mb-3">
                            <label for="email" class="form-label font-weight-bold">E-mail</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input id="email" type="email" name="email" value="{{ old('email') }}"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="seuemail@exemplo.com" required />
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Senha -->
                        <div class="form-group mb-3">
                            <label for="password" class="form-label font-weight-bold">Senha</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input id="password" type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" placeholder="••••••••"
                                    required />
                                <button class="btn btn-outline-secondary toggle-password" type="button"
                                    data-target="password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirmar Senha -->
                        <div class="form-group mb-4">
                            <label for="password_confirmation" class="form-label font-weight-bold">Confirmar Senha</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-shield-alt"></i></span>
                                <input id="password_confirmation" type="password" name="password_confirmation"
                                    class="form-control" placeholder="••••••••" required />
                                <button class="btn btn-outline-secondary toggle-password" type="button"
                                    data-target="password_confirmation">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg button_submit_login">
                                Finalizar Cadastro
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const registerForm = document.getElementById('register-form');
            const toggleBtns = document.querySelectorAll('.toggle-password');

            toggleBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    const icon = this.querySelector('i');

                    if (input) {
                        const isPassword = input.getAttribute('type') === 'password';
                        input.setAttribute('type', isPassword ? 'text' : 'password');

                        if (icon) {
                            icon.classList.toggle('fa-eye', !isPassword);
                            icon.classList.toggle('fa-eye-slash', isPassword);
                        }
                    }
                });
            });

            if (registerForm) {
                registerForm.addEventListener('submit', function() {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML =
                            '<i class="fas fa-spinner fa-spin me-2"></i> Criando conta...';
                    }
                });
            }
        });
    </script>
</body>

</html>