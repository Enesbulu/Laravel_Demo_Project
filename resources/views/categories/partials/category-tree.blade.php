{{-- resources/views/categories/partials/category-tree.blade.php --}}
<li>
    <div class="category-item d-flex align-items-center justify-content-between p-2 rounded shadow-sm">

        {{-- Kategori Bilgisi --}}
        <div>
            {{-- Kategori Derinliğini göstermek için basit bir işaretleyici --}}
            @php
                // Parent varsa bir boşluk bırakırız.
                // Bu kod, hiyerarşi derinliğini simgelemek için basit bir yaklaşımdır.
                $level = 0;
                $p = $category;
                while ($p->parent_id) {
                    $level++;
                    $p = $p->parent; // Eager Loading sayesinde bu ilişkiye erişim hızlıdır.
                }
            @endphp

            {{-- Hiyerarşi boşluğu --}}
            {!! str_repeat('<span class="me-3"></span>', $level) !!}

            <i class="me-2 {{ $category->children->isNotEmpty() ? 'fas fa-folder-open' : 'fas fa-tag' }}"></i>

            <strong class="text-dark">{{ $category->name }}</strong>
            <span class="text-muted small ms-3">/ {{ $category->slug }}</span>

            @if ($category->is_active)
                <span class="badge bg-success ms-2">Aktif</span>
            @else
                <span class="badge bg-secondary ms-2">Pasif</span>
            @endif
            
            <a href="{{ route('categories.edit', $category->slug) }}" style=" float: right inline-end; "
                class="btn btn-warning btn-sm  ms-2">Düzenle</a>
            <form action="{{ route('categories.destroy', $category->slug) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE') {{-- Burası kritik! POST isteğini DELETE isteğine dönüştürür. --}}
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Emin misiniz?')">
                    Sil
                </button>
            </form>
        </div>


    </div>

    {{-- RECURSIVE ÇAĞRI: Eğer alt kategorileri varsa, kendini tekrar çağır --}}
    @if ($category->children->isNotEmpty())
        <ul class="list-unstyled" style="padding-left: 0px; margin-top: 5px;">
            @foreach ($category->children as $child)
                {{-- Özyinelemeli olarak category-tree'yi çağır --}}
                @include('categories.partials.category-tree', ['category' => $child])
            @endforeach
        </ul>
    @endif
</li>
