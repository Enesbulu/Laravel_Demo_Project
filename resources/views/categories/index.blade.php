{{-- resources/views/categories/index.blade.php --}}


<head>
    <meta charset="utf-8" />
    <title>{{ __('messages.category_management') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script> --}}

    <style>
        /* 2. GÖRSEL EFEKTLER (Bunu style içine ekle) */
        /* Sürüklenen öğenin arkada kalan hayaleti */
        .sortable-ghost {
            opacity: 0.4;
            background-color: #f8f9fa;
            border: 1px dashed #ccc;
        }

        /* Sürüklenen öğenin kendisi */
        .sortable-drag {
            cursor: grabbing;
        }

        /* Tutma kolu imleci */
        .handle {
            cursor: grab;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">

                <h1 class="mb-4">
                    {{ __('messages.category_management') }}
                </h1>

                {{-- Yeni Kategori Butonu --}}
                <div class="mb-4">
                    <a href="{{ route('categories.create') }}" class="btn btn-success">
                        + {{ __('messages.create_new_category') }}
                    </a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif


                {{-- LİSTE YAPISI BAŞLANGICI --}}
                <div class="card">
                    <div class="card-header bg-light fw-bold">
                        {{ __('messages.category_name') }} / {{ __('messages.actions') }}
                    </div>

                    <div class="card-body p-0">
                        {{-- 3. ANA LİSTE GÜNCELLEMESİ --}}
                        {{-- ID: 'sortable-categories' ve Class: 'nested-sortable' ekledik --}}
                        <ul class="list-group list-group-flush nested-sortable" id="sortable-categories">
                            @forelse($categories as $category)
                                @include('categories.partials.category-item', ['category' => $category])
                            @empty
                                <li class="list-group-item text-center">{{ __('messages.no_categories_found') }}</li>
                            @endforelse
                        </ul>
                        {{-- Ana UL Listesi --}}
                        {{-- @dd($categories) --}}
                        {{-- <ul class="list-group list-group-flush" id="sortable-categories">
                            @for ($i = 0; $i < $categories . length; $Fi++)
                             { new Sortable(categories[$i], {
                                group: 'nested' , 
                                animation: 150,
                                 fallbackOnBody: true,
                                  swapThreshold: 0.65 }
                                  );
                             } --}}
                        {{-- {{-- Recursive Parçayı Çağır  --}}
                        {{-- @forelse($categories as $category)  
                                @include('categories.partials.category-item', ['category' => $category]) 
                            @empty
                                <li class="list-group-item text-center text-muted py-3">
                                    {{ __('messages.no_categories_found') }}
                                </li> @endforelse
                                </ul> --}}
                    </div>
                </div>
                {{-- /LİSTE YAPISI BİTİŞİ --}}

                {{-- Sayfalama --}}
                {{-- <div class="d-flex justify-content-center mt-4">
                    {{ $categories->links() }}
                </div> --}}

            </div>
        </div>
    </div>
    {{-- 4. JAVASCRIPT BAŞLATMA KODU (Dosyanın en altına) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sayfadaki "nested-sortable" sınıfına sahip TÜM listeleri (ana ve alt) bul
            var nestedSortables = [].slice.call(document.querySelectorAll('.nested-sortable'));

            // Her bir liste için Sortable'ı başlat
            for (var i = 0; i < nestedSortables.length; i++) {
                new Sortable(nestedSortables[i], {
                    group: 'nested', // KRİTİK: Hepsi aynı grupta olmalı ki iç içe geçebilsinler
                    animation: 150,
                    fallbackOnBody: true,
                    swapThreshold: 0.65,
                    handle: '.handle', // Sadece ikondan tutunca sürüklensin istiyorsan bunu aç

                    // Sürükleme bittiğinde çalışacak (İleride burayı kullanacağız)
                    onEnd: function(evt) {
                        console.log('Öğe taşındı:', evt.item);
                    }
                });
            }
        });
    </script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
</body>
