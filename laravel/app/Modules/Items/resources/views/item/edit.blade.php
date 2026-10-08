<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }} | Editar Item</title>

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
                    <span class="small"><i class="fa-solid fa-user me-1"></i> {{ Auth::user()->name ?? 'Usuário' }}</span>
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
            <div class="col-lg-8">

                {{-- Cabeçalho da página --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 font-weight-bold text-dark mb-1">Editar Item #{{ $item->id }}</h1>
                        <p class="text-muted mb-0">Atualize os detalhes ou altere o status do item.</p>
                    </div>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Voltar
                    </a>
                </div>

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

                <div class="card border-0 shadow-sm mb-5">
                    <div class="card-body p-4">
                        <form action="{{ route('items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="title" class="form-label font-weight-bold">Título do Item <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="title" 
                                       id="title" 
                                       class="form-control @error('title') is-invalid @enderror" 
                                       value="{{ old('title', $item->title) }}" 
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="category" class="form-label font-weight-bold">Categoria <span class="text-danger">*</span></label>
                                    <select name="category" id="category" class="form-select @error('category') is-invalid @enderror" required>
                                        <option value="eletronicos" {{ old('category', $item->category) == 'eletronicos' ? 'selected' : '' }}>Eletrônicos</option>
                                        <option value="documentos" {{ old('category', $item->category) == 'documentos' ? 'selected' : '' }}>Documentos</option>
                                        <option value="vestuario" {{ old('category', $item->category) == 'vestuario' ? 'selected' : '' }}>Vestuário</option>
                                        <option value="outros" {{ old('category', $item->category) == 'outros' ? 'selected' : '' }}>Outros</option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="status" class="form-label font-weight-bold">Status <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="encontrado" {{ old('status', $item->status) == 'encontrado' ? 'selected' : '' }}>Encontrado</option>
                                        <option value="perdido" {{ old('status', $item->status) == 'perdido' ? 'selected' : '' }}>Perdido</option>
                                        <option value="devolvido" {{ old('status', $item->status) == 'devolvido' ? 'selected' : '' }}>Devolvido ao Dono</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label font-weight-bold">Descrição Detalhada <span class="text-danger">*</span></label>
                                <textarea name="description" 
                                          id="description" 
                                          rows="4" 
                                          class="form-control @error('description') is-invalid @enderror" 
                                          required>{{ old('description', $item->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="image" class="form-label font-weight-bold">Trocar Foto (Opcional)</label>
                                <input type="file" 
                                       name="image" 
                                       id="image" 
                                       class="form-control @error('image') is-invalid @enderror" 
                                       accept="image/jpeg,image/png,image/jpg,image/webp">
                                <small class="text-muted d-block mt-1">Deixe em branco para manter a foto atual.</small>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div class="row mt-3">
                                    @if($item->image_path)
                                        <div class="col-md-6 mb-2">
                                            <p class="small text-muted mb-1">Foto Atual:</p>
                                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="Foto Atual" class="rounded border shadow-sm" style="max-height: 180px; object-fit: cover;">
                                        </div>
                                    @endif
                                    <div id="image-preview-container" class="col-md-6 mb-2 d-none">
                                        <p class="small text-muted mb-1">Nova Foto (Pré-visualização):</p>
                                        <img id="image-preview" src="#" alt="Nova Foto" class="rounded border shadow-sm" style="max-height: 180px; object-fit: cover;">
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('dashboard') }}" class="btn btn-light border">Cancelar</a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save me-1"></i> Salvar Alterações
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

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