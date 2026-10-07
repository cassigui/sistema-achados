<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }} | Login</title>

    <link rel="stylesheet" href="{{ asset('assets/css/auth.min.css?v=1.0') }}" />
    
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
                            <i class="fa-solid fa-user-lock fa-2x text-primary"></i>
                        </div>
                        <h1 class="title_login h3 font-weight-bold">Acessar sua Conta</h1>
                        <p class="text_login text-muted">
                            Informe seu e-mail e senha para continuar navegando no sistema.
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

                    <form method="POST" action="{{ route('login') }}" id="login-form">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="email" class="form-label font-weight-bold">E-mail</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input id="email" 
                                       type="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       placeholder="seuemail@exemplo.com"
                                       required 
                                       autofocus />
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password" class="form-label font-weight-bold">Senha</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input id="password" 
                                       type="password" 
                                       name="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       placeholder="••••••••"
                                       required />
                                <button class="btn btn-outline-secondary" type="button" id="toggle-password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-decoration-none small text-primary">
                                    Esqueceu a senha?
                                </a>
                            @endif
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg button_submit_login">
                                Entrar na Conta
                            </button>
                        </div>

                        @if (Route::has('register'))
                            <p class="text-center text-muted mt-3 mb-0">
                                Ainda não tem uma conta? 
                                <a href="{{ route('register') }}" class="text-primary text-decoration-none font-weight-bold">Cadastre-se</a>
                            </p>
                        @endif

                    </form>

                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const loginForm = document.getElementById('login-form');
            const togglePasswordBtn = document.getElementById('toggle-password');
            const passwordInput = document.getElementById('password');

            if (togglePasswordBtn && passwordInput) {
                togglePasswordBtn.addEventListener('click', function () {
                    const icon = this.querySelector('i');
                    const isPassword = passwordInput.getAttribute('type') === 'password';

                    passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                    
                    if (icon) {
                        icon.classList.toggle('fa-eye', !isPassword);
                        icon.classList.toggle('fa-eye-slash', isPassword);
                    }
                });
            }

            if (loginForm) {
                loginForm.addEventListener('submit', function () {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Entrando...';
                    }
                });
            }
        });
    </script>
</body>
</html>