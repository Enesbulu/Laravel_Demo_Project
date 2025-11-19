{{-- resources/views/categories/partials/category-row.blade.php --}}

{{-- $category: Şu anki kategori objesi (Eloquent Model) --}}
{{-- $level: Hiyerarşi derinliği (0 = Ana Kategori) --}}
<tr class="{{ $level == 0 ? 'table-warning' : '' }}">


    {{-- 1. SIRA NUMARASI --}}
    <td>
        @if ($level == 0)
            {{ $counter }}
        @else
            {{-- Alt kategoriler için sıra numarası göstermiyoruz, sadece görsel hiyerarşi önemlidir. --}}
        @endif
    </td>
    {{-- 1. KATEGORİ ADI / SLUG (Hiyerarşi Gösterimi) --}}
    <td>
        {{-- Hiyerarşi Girintisi: Derinlik * 2 boşluk bırakır --}}
        @if ($level > 0)
            {!! str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level) !!}
            <i class="fas fa-level-up-alt fa-rotate-90 me-2 text-muted"></i>
        @endif

        <strong class="{{ $level == 0 ? 'text-primary' : '' }}">
            {{ $category->name }}
        </strong>
        <br>
        <span class="text-muted small ms-4">Slug: {{ $category->slug }}</span>
    </td>

    {{-- 2. ÜST KATEGORİ --}}
    <td>
        {{-- {{ $category->full_path}} --}}

        {{ $category->full_path[app()->getLocale()] ?? $category->full_path }}
        

        {{-- @if ($category->parent)
            {{ $category->parent->name }}
        @else
            <span class="badge bg-secondary">Ana Kategori</span>
        @endif --}}
    </td>

    <td>
        {{ $category->description }}
    </td>
    {{-- 3. DURUM --}}
    <td>
        @if ($category->is_active)
            <span class="badge bg-success">Aktif</span>
        @else
            <span class="badge bg-danger">Pasif</span>
        @endif
    </td>

    {{-- 4. İŞLEMLER --}}
    <td>
        <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-info me-1">
            {{ __('messages.edit') }}
        </a>
        <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger"
                onclick="return confirm('{{ __('messages.confirm_delete_msg') }}')">
                {{ __('messages.delete') }}
            </button>
        </form>
    </td>
</tr>


{{-- ÖZYİNELEMELİ ÇAĞRI: Eğer bu kategorinin alt kategorileri varsa... --}}
@if ($category->childrenRecursive->isNotEmpty())
    {{-- Her bir alt kategori için bu parçayı tekrar çağır --}}
    @foreach ($category->childrenRecursive as $child)
        {{-- Level'ı 1 artırarak tekrar çağırıyoruz --}}
        @include('categories.partials.category-row', [
            'category' => $child,
            'level' => $level + 1,
        ])
    @endforeach
@endif
