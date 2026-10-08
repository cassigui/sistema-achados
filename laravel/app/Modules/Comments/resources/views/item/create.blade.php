<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }} | Cadastrar Comment</title>

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
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                </ul>
                <div class="d-flex align-comments-center gap-3 text-white">
                    <span class="small"><i class="fa-solid fa-user me-1"></i> {{ Auth::user()->name ?? 'Usuário' }}</span>
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
        <div class="row justify-content-center">
            <div class="col-lg-8">

                {{-- Cabeçalho da página --}}
                <div class="d-flex justify-content-between align-comments-center mb-4">
                    <div>
                        <h1 class="h3 font-weight-bold text-dark mb-1">Registrar Novo Comment</h1>
                        <p class="text-muted mb-0">Cadastre um objeto achado ou perdido no campus da UTFPR.</p>
                    </div>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Voltar
                    </a>
                </div>

                {{-- Exibição de erros gerais --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <strong><i class="fas fa-exclamation-triangle me-1"></i> Por favor, corrija os erros abaixo:</strong>
                        <ul class="mb-0 mt-2 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Formulário de Cadastro --}}
                <div class="card border-0 shadow-sm mb-5">
                    <div class="card-body p-4">
                        <form action="{{ route('comments.store') }}" method="POST" enctype="multipart/form-data" id="form-create-comment">
                            @csrf

                            {{-- Título do Comment --}}
                            <div class="mb-3">
                                <label for="title" class="form-label font-weight-bold">Título do Comment <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="title" 
                                       id="title" 
                                       class="form-control @error('title') is-invalid @enderror" 
                                       placeholder="Ex: Pendrive Kingston 32GB preto, Garrafa Térmica Azul, etc." 
                                       value="{{ old('title') }}" 
                                       required 
                                       autofocus>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row g-3 mb-3">
                                {{-- Categoria --}}
                                <div class="col-md-6">
                                    <label for="category" class="form-label font-weight-bold">Categoria <span class="text-danger">*</span></label>
                                    <select name="category" id="category" class="form-select @error('category') is-invalid @enderror" required>
                                        <option value="" disabled {{ old('category') ? '' : 'selected' }}>Selecione a categoria...</option>
                                        <option value="eletronicos" {{ old('category') == 'eletronicos' ? 'selected' : '' }}>Eletrônicos</option>
                                        <option value="documentos" {{ old('category') == 'documentos' ? 'selected' : '' }}>Documentos</option>
                                        <option value="vestuario" {{ old('category') == 'vestuario' ? 'selected' : '' }}>Vestuário</option>
                                        <option value="outros" {{ old('category') == 'outros' ? 'selected' : '' }}>Outros</option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Tipo --}}
                                <div class="col-md-6">
                                    <label for="status" class="form-label font-weight-bold">Tipo <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="encontrado" {{ old('status', 'encontrado') == 'encontrado' ? 'selected' : '' }}>Encontrado (Achei no campus)</option>
                                        <option value="perdido" {{ old('status') == 'perdido' ? 'selected' : '' }}>Perdido (Perdi no campus)</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Descrição --}}
                            <div class="mb-3">
                                <label for="description" class="form-label font-weight-bold">Descrição Detalhada <span class="text-danger">*</span></label>
                                <textarea name="description" 
                                          id="description" 
                                          rows="4" 
                                          class="form-control @error('description') is-invalid @enderror" 
                                          placeholder="Descreva detalhes como cor, marcas de uso, local exato onde foi visto ou encontrado (ex: Laboratório D02, Refeitório, etc.)..." 
                                          required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Upload de Foto --}}
                            <div class="mb-4">
                                <label for="image" class="form-label font-weight-bold">Foto do Comment (Opcional)</label>
                                <input type="file" 
                                       name="image" 
                                       id="image" 
                                       class="form-control @error('image') is-invalid @enderror" 
                                       accept="image/jpeg,image/png,image/jpg,image/webp">
                                <small class="text-muted d-block mt-1">Formatos aceitos: JPG, PNG, WEBP. Tamanho máximo: 2MB.</small>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                {{-- Container de Pré-visualização --}}
                                <div id="image-preview-container" class="mt-3 d-none">
                                    <p class="small text-muted mb-1">Pré-visualização da Imagem:</p>
                                    <img id="image-preview" src="#" alt="Pré-visualização" class="rounded border shadow-sm" style="max-height: 200px; object-fit: cover;">
                                </div>
                            </div>

                            <hr class="my-4">

                            {{-- Botões de Ação --}}
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('dashboard') }}" class="btn btn-light border">Cancelar</a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save me-1"></i> Salvar Registro
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Script de Pré-visualização da Imagem --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const imageInput = document.getElementById('image');
            const previewContainer = document.getElementById('image-preview-container');
            const previewImage = document.getElementById('image-preview');

            if (imageInput && previewContainer && previewImage) {
                imageInput.addEventListener('change', function () {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            previewImage.setAttribute('src', e.target.result);
                            previewContainer.classList.remove('d-none');
                        }
                        reader.readAsDataURL(file);
                    } else {
                        previewContainer.classList.add('d-none');
                        previewImage.setAttribute('src', '#');
                    }
                });
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>