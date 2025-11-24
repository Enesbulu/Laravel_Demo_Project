{{-- resources/views/categories/create.blade.php --}}
@php
    $locales = ['tr', 'en'];
@endphp

<head>
    <meta charset="utf-8" />
    <title>{{ __('messages.create_category_title') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">

                <h1 class="mb-4">{{ __('messages.create_category_title') }}</h1>

                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf   <!-- {{-- Bu satır, formu gönderirken gizli bir input alanı (<input type="hidden" name="_token" value="xxxx">) ekler --}} -->

                    {{-- Geri Dön Butonu --}}
                    <div class="mb-3">
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_list') }}
                        </a>
                    </div>

                    {{-- Kategori oluşturma formunda genel ayarların (üst kategori seçimi ve aktiflik durumu) yer aldığı kart bileşeni --}}
                    <div class="card mb-4">
                        <div class="card-header">{{ __('messages.general_settings') }}</div>
                        <div class="card-body">
                            {{-- Üst Kategori Seçimi --}}
                            <div class="mb-3">
                                <label for="parent_id" class="form-label">{{ __('messages.parent_category') }}</label>
                                <select id="parent_id" name="parent_id"
                                    class="form-select @error('parent_id') is-invalid @enderror">
                                    @foreach ($parentCategories as $id => $name)
                                        <option value="{{ $id }}"
                                            {{ old('parent_id') == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">{{ __('messages.parent_category_help') }}</div>
                                @error('parent_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Aktif Durumu --}}
                            <div class="mb-3 form-check">
                                <input type="checkbox" id="is_active" name="is_active" class="form-check-input"
                                    value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">{{ __('messages.is_active') }}</label>
                                <input type="hidden" name="is_active" value="1">
                            </div>
                        </div>
                    </div>
                    {{-- Kategori formunda çoklu dil desteği için sekmeli (tab) alanları gösteren kart bileşeni --}}
                    <div class="card">
                        <div class="card-header">{{ __('messages.translatable_fields') }}</div>
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                @foreach ($locales as $index => $locale)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link {{ $index == 0 ? 'active' : '' }}"
                                            id="{{ $locale }}-tab" data-bs-toggle="tab"
                                            data-bs-target="#{{ $locale }}-tab-pane" type="button"
                                            role="tab">{{ strtoupper($locale) }}</button>
                                    </li>
                                @endforeach
                            </ul>

                            {{-- Kategori formunda her dil için ad, slug ve açıklama alanlarını sekmeli (tab) olarak gösteren içerik bölümü --}}
                            <div class="tab-content p-3 border border-top-0" id="myTabContent">
                                @foreach ($locales as $index => $locale)
                                    <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}"
                                        id="{{ $locale }}-tab-pane" role="tabpanel">

                                        {{-- Kategori Adı [tr], [en] --}}
                                        <div class="mb-3">
                                            <label for="name_{{ $locale }}"
                                                class="form-label">{{ __('messages.category_name') }}
                                                ({{ strtoupper($locale) }})
                                            </label>
                                            <input type="text" id="name_{{ $locale }}"
                                                name="name[{{ $locale }}]"
                                                class="form-control @error('name.' . $locale) is-invalid @enderror"
                                                value="{{ old('name.' . $locale) }}">
                                            @error('name.' . $locale)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Slug [tr], [en] --}}
                                        {{-- <div class="mb-3">
                                            <label for="slug_{{ $locale }}"
                                                class="form-label">{{ __('messages.category_slug') }}
                                                ({{ strtoupper($locale) }})</label>
                                            <input type="text" id="slug_{{ $locale }}"
                                                name="slug[{{ $locale }}]"
                                                class="form-control @error('slug.' . $locale) is-invalid @enderror"
                                                value="{{ old('slug.' . $locale) }}">
                                            @error('slug.' . $locale)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div> --}}

                                        {{-- Açıklama [tr], [en] --}}
                                        <div class="mb-3">
                                            <label for="description_{{ $locale }}"
                                                class="form-label">{{ __('messages.description') }}
                                                ({{ strtoupper($locale) }})</label>
                                            <textarea id="description_{{ $locale }}" name="description[{{ $locale }}]"
                                                class="form-control @error('description.' . $locale) is-invalid @enderror" rows="3">{{ old('description.' . $locale) }}</textarea>
                                            @error('description.' . $locale)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i>  {{ __('messages.save_category') }}</button>
                </form>
            </div>
        </div>
    </div>
    <section>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
            integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
            integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    </section>
</body>
