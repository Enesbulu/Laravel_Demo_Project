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
            background-color: #125599;
            border: 1px dashed #cf1919;
        }

        /* Sürüklenen öğenin kendisi */
        .sortable-drag {
            cursor: grabbing;
        }

        /* Tutma kolu imleci */
        .handle {
            cursor: grab;
        }

        /* KRİTİK CSS: Boş listelere alan açar */
        .nested-sortable {
            min-height: 2px;
            /* Boş olsa bile en az 40px yükseklik kapla */
            padding-bottom: 1px;
            /* Biraz boşluk bırak */
            background-color: #d6fc50f3;
        }

        /* İsteğe bağlı: Boş alanın nereye bırakılacağını belli etmek için */
        .nested-sortable:empty {
            border: 1px dashed #e5e5e5;
            /* Sadece boşken ince bir çerçeve göster */
            border-radius: 5px;
            margin-top: 5px;
            background-color: #9aee4baf
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

    {{-- AKSİYON ÇUBUĞU (Başlangıçta Gizli: d-none) --}}
    <div id="changes-bar" class="fixed-bottom bg-white border-top shadow p-3 d-none" style="z-index: 1000;">
        <div class="container d-flex justify-content-between align-items-center">

            <div class="text-warning fw-bold">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ __('messages.unsaved_changes') ?? 'Kaydedilmemiş değişiklikler var!' }}
            </div>

            <div>
                {{-- Sıfırla Butonu --}}
                <button id="btn-reset" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-undo me-1"></i> {{ __('messages.reset') ?? 'Sıfırla' }}
                </button>

                {{-- Kaydet Butonu --}}
                <button id="btn-save-order" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> {{ __('messages.save_changes') ?? 'Değişiklikleri Kaydet' }}
                </button>
            </div>

        </div>
    </div>


    {{-- 4. JAVASCRIPT BAŞLATMA KODU (Dosyanın en altına) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            //Elemanları seçme
            var changesBar = document.getElementById('changes-bar');
            var btnReset = document.getElementById('btn-reset');
            var btnSaveOrder = document.getElementById('btn-save-order');
            // Sayfadaki "nested-sortable" sınıfına sahip TÜM listeleri (ana ve alt) bul
            var nestedSortables = [].slice.call(document.querySelectorAll('.nested-sortable'));

            function serializeList(container) {
                var items = [];
                var lis = Array.from(container.children).filter(node => node.tagName === 'LI');
                lis.forEach(function(li) {
                    var item = {
                        id: li.getAttribute('data-id'),
                        children: []
                    };
                    var childList = li.querySelector('.nested-sortable');
                    if (childList) {
                        item.children = serializeList(childList);
                    }
                    items.push(item);
                });
                return items;
            }

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
                        showChangesBar();
                    }
                });
            }

            function showChangesBar() {
                changesBar.classList.remove('d-none');
            }

            btnReset.addEventListener('click', function() {
                if (confirm('Are you sure you want to reset the changes?')) {
                    window.location.reload();
                }
            });

            btnSaveOrder.addEventListener('click', function() {
                var originalText = this.innerHTML;
                this.innerHTML =
                    '<i class="fas fa-spinner fa-spin me-1"></i> {{ __('messages.saving') ?? 'Kaydediliyor...' }}';
                this.disabled = true;

                var rootList = document.getElementById('sortable-categories');
                var treeData = serializeList(rootList);

                fetch("{{ route('categories.reorder') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            "Accept": "application/json" // Laravel'e JSON beklediğimizi söylüyoruz
                        },
                        body: JSON.stringify({
                            tree: treeData
                        })
                    })
                    .then(function(response) {
                        // HTTP Durumunu Kontrol Et (200 OK mi?)
                        if (!response.ok) {
                            // Eğer sunucu hata döndürürse, hatayı yakalamak için fırlat
                            throw new Error('Sunucu Hatası: ' + response.status);
                        }
                        return response.json();
                    })
                    .then(function(data) {
                        console.log('Sunucu Yanıtı:', data); // Konsolda yanıtı görelim
                        if (data.status === 'success') {
                            window.location.reload();
                            document.getElementById('changes-bar').classList.add('d-none');
                            btnSaveOrder.innerHTML = originalText;
                            btnSaveOrder.disabled = false;
                        } else {
                            alert('An error occurred while saving the order.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while saving the order.');
                        this.innerHTML = originalText;
                        this.disabled = false;
                    })
                    .finally(function() {
                        btnSaveOrder.innerHTML = originalText;
                        btnSaveOrder.disabled = false;
                    });;
            });
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
</body>
