{{-- resources/views/categories/index.blade.php --}}

{{-- Ana sayfa görünümü: kategori ağacını (hierarchical category tree) gösterir. --}}
<head>
    <meta charset="utf-8" />
    <title>Kategori Yönetimi</title>
    {{-- Bootstrap CSS dahil ediliyor (CDN) - sayfa stil ve layoutları için kullanılır --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    
    <style>
        /* Hiyerarşiyi görselleştirmek için basit CSS eklentileri */
        /* .category-item: her bir kategori satırının görünümü (arka plan, kenar vb.) */
        .category-item {
            background-color: #f8f9fa;
            border-left: 5px solid #0d6efd !important;
            margin-bottom: 8px;
        }
        /* category-tree içinde alt listeler için madde işaretlerini kaldırma */
        .category-tree ul {
            list-style: none; /* Alt listelerin varsayılan madde işaretini kaldırır */
        }
        /* Her kategori öğesinin üst boşluğu */
        .category-tree li {
            margin-top: 5px;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="container">
                <h1>Kategori Yönetimi <span class="badge bg-primary fs-6">Hiyerarşik</span></h1>
                <button-primary><a href="{{ route('categories.create') }}">Yeni Kategori Ekle</a></button-primary>    
            </div>
                {{--
                    Başarı mesajı (flash): Controller'da session()->flash('success', '...')
                    gibi bir değer varsa kullanıcıya gösterilir. Bu Blade koşulu sadece gösterim amaçlıdır.
                --}}
                @if(session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                {{--
                    Kategori ağacının başlangıç noktası: $categories koleksiyonu
                    genellikle yalnızca üst (root) kategorileri içerir ve partial özyinelemeli olarak
                    tüm alt dalları render eder. @forelse, koleksiyon boşsa alternatif içerik gösterir.
                --}}
                <ul class="list-unstyled category-tree">
                    @forelse($categories as $category)
                        {{-- Her bir ana kategori için özyinelemeli partial çağrılır (recursive include) --}}
                        @include('categories.partials.category-tree', ['category' => $category])
                    @empty
                        <div class="alert alert-info">
                            Veritabanında hiç ana kategori bulunamadı.
                        </div>
                    @endforelse
                    
                </ul>

            </div>
        </div>
    </div>

    {{-- Popper ve Bootstrap JS (etkileşimli bileşenler için) --}}
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>