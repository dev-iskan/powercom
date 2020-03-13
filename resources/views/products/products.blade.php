@extends('layout.index')

@section('meta')
    <meta name="description" content="Интернет магазин">
    <meta name="og:title" property="og:title" content="Интернет магазин">
    <meta name="twitter:card" content="Интернет магазин">
@endsection

@section('body')
    <section class="section">
        <div class="container">
            <div class="columns is-multiline">
                <div class="column is-one-third">
                    <div class="is-size-5">Фильтры</div>
                    <hr>
                    @if(count($brands))
                        <div class="is-size-5 mb-10">Бренды</div>
                        <div class="filter-contianer content p-10 has-background-white-ter">
                            <ul style="list-style: none; margin: 0;">
                                @foreach ($brands as $brand)
                                <li>
                                    <label class="checkbox">
                                        <input type="checkbox" class="brand" onchange="search()" value="{{ $brand->id }}">
                                        {{ $brand->name }}
                                    </label>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if(count($categories))
                    <div class="is-size-5 mb-10">Категории</div>
                    <div class="filter-contianer content has-background-white-ter p-10">
                        <ul style="list-style: none; margin: 0;">
                            @foreach ($categories as $category)
                                <li>
                                    <label class="checkbox">
                                        <input type="checkbox" class="category" onchange="search()" value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </label>
                                </li>
                                <ul style="list-style: none;">
                                    @foreach ($category->allChildren as $childCategory)
                                        @include('components.categories', ['category' => $childCategory])
                                    @endforeach
                                </ul>   
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <input class="input" id="query" type="text" placeholder="Введите ключевое слово">
                    <button onclick="search()" class="button is-fullwidth is-primary mt-10">Найти</button>
                </div>
                <div class="column is-two-third">
                    <div class="is-size-5">
                        Товары
                        @foreach($query['categories'] as $category)
                            @if($category)
                                <span class="tag">
                                    {{ $category->name }}
                                    <button class="delete" onclick="remove('categories', '{!! $category->id !!}')"></button>
                                </span>
                            @endif
                        @endforeach
                        @foreach($query['brands'] as $brand)
                            @if($brand)
                                <span class="tag">
                                    {{ $brand->name }}
                                    <button class="delete" onclick="remove('brands', '{!! $brand->id !!}')"></button>
                                </span>
                            @endif
                        @endforeach
                        @if($query['q'])
                        <span class="tag">
                            {{ $query['q'] }}
                            <button class="delete" onclick="remove('q')"></button>
                        </span>
                        @endif
                    </div>
                    <hr>
                    @if (!count($products))
                        <div class="is-size-4">Продукты не найдены</div>
                    @endif
                    <div class="container">
                        <div class="columns is-multiline">
                            @foreach ($products as $product)
                                <div class="column is-one-third-desktop is-half-tablet">
                                    @include('components.card', ['product' => $product])
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
<script>
    function setChecked() {
        let categories = [];
        let brands = [];
        let queryText = '';

        let params = window.location.search.substr(1, window.location.search.length - 1);
        params = params.split('&');
        
        params.forEach(param => {
            const [key, value] = param.split('=');
            switch(key) {
                case 'categories':
                    categories = value.split(';');
                    break;
                case 'brands':
                    brands = value.split(';');
                    break;
                case 'q':
                    queryText = value;
                    break;
            }
        });

        if (categories.length) {
            categories.forEach(id => {
                const elements = document.querySelectorAll('.category');
                elements.forEach(element => {
                    if(element.value === id) {
                        element.checked = true;
                    }
                });
            })
        }

        if (brands.length) {
            brands.forEach(id => {
                const elements = document.querySelectorAll('.brand');
                elements.forEach(element => {
                    if(element.value === id) {
                        element.checked = true;
                    }
                });
            })
        }

        if (queryText) {
            const element = document.getElementById('query');
            element.value = queryText;
        }
    }

    function search() {
        const brands = document.querySelectorAll('.brand');
        const categories = document.querySelectorAll('.category');
        const queryText = document.getElementById('query');
        
        let selectedBrands = [];
        let selectedCategories = [];

        brands.forEach(element => {
            if(element.checked) {
                selectedBrands.push(element.value);
            }
        });

        categories.forEach(element => {
            if(element.checked) {
                selectedCategories.push(element.value);
            }
        });
        
        let query = [];
        if (selectedBrands.length) {
            query.push('brands=' + selectedBrands.join(';'))
        }
        if (selectedCategories.length) {
            query.push('categories=' + selectedCategories.join(';'))
        }
        if (queryText.value) {
            query.push('q=' + queryText.value)
        }
        query = query.join('&');

        window.location.replace('{!! route('products.index') !!}' + '?' + query);
    }

    function remove(type, value) {
        switch(type) {
            case 'brands':
                const brands = document.querySelectorAll('.brand');
                brands.forEach(element => {
                    if(element.value === value) {
                        element.checked = false;
                    }
                });
                break;
            case 'categories':
                const categories = document.querySelectorAll('.category');
                categories.forEach(element => {
                    if(element.value === value) {
                        element.checked = false;
                    }
                });
                break;
            case 'q':
                const queryText = document.getElementById('query');
                queryText.value = '';
                break;
        }
        search();
    }

    setChecked();
</script>
@endsection