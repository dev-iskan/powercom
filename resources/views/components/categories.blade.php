<li>
    <label class="checkbox">
        <input type="checkbox" class="category" onchange="search({{ $category->id }})" value="{{ $category->id }}">
        {{ $category->name }}
    </label>
</li>
@if ($category->children)
    <ul style="list-style: none;">
        @foreach ($category->children as $childCategory)
            @include('components.categories', ['category' => $childCategory])
        @endforeach
    </ul>
@endif