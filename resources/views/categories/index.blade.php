<head>
    <meta charset="utf-8" />
    <title>{{ __('messages.category_management') }}</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>

<body>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>{{ __('messages.category_management') }}</h1>
                <a href="{{ route('categories.create') }}" class="btn btn-success">
                    + {{ __('messages.create_new_category') }}
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <table id="categories-table" class="table table-bordered table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>{{ __('messages.category_name') }}</th>
                                <th>Slug</th>
                                <th>{{ __('messages.parent_category') }}</th>
                                <th>{{ __('messages.status') }}</th>
                                <th style="width: 150px;">{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        // Aktif dile göre DataTables dil dosyasını seçelim (Opsiyonel ama şık olur)
        var langUrl = "//cdn.datatables.net/plug-ins/1.13.6/i18n/tr.json";
        // Eğer App locale 'en' ise URL'i boş bırakabiliriz veya en.json verebiliriz.
        
        $('#categories-table').DataTable({
            processing: true, // "Yükleniyor" yazısı
            serverSide: true, // Sunucu taraflı işlem (AJAX)
            ajax: "{{ route('categories.index') }}", // Veri kaynağı
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' }, // JSON olduğu için arama/sıralama özel ayar gerektirebilir
                { data: 'slug', name: 'slug' },
                { data: 'parent_name', name: 'parent.name', orderable: false }, // İlişkisel sütun
                { data: 'is_active', name: 'is_active' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            language: {
                url: langUrl
            }
        });
    });
</script>

</body>

{{-- !!!!!--!!!! 
    -- Datatable tasarımı genel olarak yapılıd. Verilerin çekilmesi şekillendirilencek düzgün çekilmiyor.
    -- Action Butonlar gelmiyor düzenleme yapılacak sayfa içerisinde.
    -- Slug verisi geçersiz geliyor düzenlenecek.
    -- Kategori-Alt Kategori yolu yok, eklenecek.    
    !!!!---!!!!! --}}