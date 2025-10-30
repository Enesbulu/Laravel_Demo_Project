{{-- resources/views/categories/create.blade.php --}}

<head>
    <meta charset="utf-8" />
    <title>Yeni Kategori Ekle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                
                <h1 class="mb-4">Yeni Kategori Ekle</h1>
                
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    
                    {{-- Geri Dön Butonu --}}
                    <div class="mb-3">
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kategori Listesine Dön
                        </a>
                    </div>

                    {{-- 1. Kategori Adı --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Kategori Adı (*)</label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name') 
                            <div class="invalid-feedback">{{ $message }}</div> 
                        @enderror
                    </div>

                    {{-- 2. Üst Kategori Seçimi (Self-Referencing) --}}
                    <div class="mb-3">
                        <label for="parent_id" class="form-label">Üst Kategori</label>
                        {{-- Controller'dan gelen $parentCategories Pluck koleksiyonunu kullanıyoruz --}}
                        <select id="parent_id" name="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
                            {{-- $parentCategories, pluck() metodu ile ID => Name formatında gelir. --}}
                            @foreach($parentCategories as $id => $name)
                                <option value="{{ $id }}" {{ old('parent_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Bir üst kategori seçilmezse (Ana Kategori), bu kategori en üst seviyede yer alır.</div>
                        @error('parent_id') 
                            <div class="invalid-feedback">{{ $message }}</div> 
                        @enderror
                    </div>

                    {{-- 3. Açıklama --}}
                    <div class="mb-3">
                        <label for="description" class="form-label">Açıklama</label>
                        <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                        @error('description') 
                            <div class="invalid-feedback">{{ $message }}</div> 
                        @enderror
                    </div>

                    {{-- 4. Aktif Durumu --}}
                    <div class="mb-3 form-check">
                        @php
                            // Checkbox için varsayılan değeri ayarlama. Eğer old('is_active') yoksa ve
                            // Modeldeki $attributes doğruysa, varsayılan değer true kabul edilir.
                            $isActiveChecked = old('is_active') !== null ? old('is_active') : true;
                        @endphp
                        <input type="checkbox" id="is_active" name="is_active" class="form-check-input" value="1" {{ $isActiveChecked ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Aktif mi?</label>
                        <input type="hidden" name="is_active" value="0" {{-- Unchecked durumda 0 göndermek için --}} >
                        @error('is_active') 
                            <div class="invalid-feedback d-block">{{ $message }}</div> 
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Kategoriyi Kaydet</button>

                </form>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>