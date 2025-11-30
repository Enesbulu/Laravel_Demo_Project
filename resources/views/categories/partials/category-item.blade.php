{{-- resources/views/categories/partials/category-item.blade.php --}}
{{-- @php dd(get_defined_vars()); @endphp --}}
<li class="list-group-item" data-id="{{ $category->id }}">
    <div class="d-flex justify-content-between align-items-center">

        <div class="d-flex align-items-center">
            {{-- Sürükleme Tutamacı (Handle) --}}
            {{-- <i class="fas fa-grip-vertical handle"></i> --}}
            <i class="fas fa-grip-vertical handle me-2 text-muted"></i>

            {{-- Kategori Bilgisi --}}
            <div>
                <span class="fw-bold text-primary">{{ $category->name }}</span>
                <small class="text-muted ms-2">(/{{ $category->slug }})</small>

                {{-- Tam Yolu Göster (Case 1'den gelen özellik) --}}
                <div class="text-muted" style="font-size: 0.75rem;">
                    <i class="fas fa-level-up-alt fa-rotate-90 me-1"></i>
                    {{ $category->full_path }}
                </div>
            </div>
        </div>

        {{-- Aksiyon Butonları --}}
        <div>
            <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-secondary' }} me-2">
                {{ $category->is_active ? __('messages.status_active') : __('messages.status_passive') }}
            </span>

            <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-outline-info me-1">
                {{ __('messages.edit') }}
            </a>

            <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger"
                    onclick="return confirm('{{ __('messages.confirm_delete_msg') }}')">
                    {{ __('messages.delete') }}
                </button>
            </form>
        </div>
    </div>

    {{-- ALT KATEGORİLER (İÇ İÇE LİSTE) --}}
    {{-- Eğer alt kategoriler varsa, yeni bir UL açıp kendini tekrar çağırır --}}
    <ul class="list-group mt-2 ms-4 border-start border-2 ps-2 nested-sortable" style="background-color: #f9f9f9">
        @if ($category->childrenRecursive->isNotEmpty())
            @foreach ($category->childrenRecursive as $child)
                @include('categories.partials.category-item', ['category' => $child])
            @endforeach
        @endif
    </ul>
    {{-- @if ($category->childrenRecursive->isNotEmpty())
        <ul class="list-group mt-2 ms-4 border-start border-2 ps-2 nested-sortable" style="background-color: #f9f9f9 ">
            @foreach ($category->childrenRecursive as $child)
                @include('categories.partials.category-item', ['category' => $child])
            @endforeach
        </ul>
    @endif --}}
</li>
