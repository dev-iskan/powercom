@extends('layout.index')

@section('meta')
    <meta name="description" content="{{ $category->name }}">
    <meta name="og:title" property="og:title" content="{{ $category->name }}">
    <meta name="twitter:card" content="{{ $category->name }}">
@endsection

@section('body')
<section class="section">
    <div class="container">
        <div class="columns is-multiline">
            <div class="column is-full">
                <h1 class="title">{{ $category->name }}</h1>
                <p class="has-text-justified">{{ $category->short_description }}</p>
                <hr>
            </div>
            <div class="column is-one-third">
                @if ($category->parent) 
                    <p>
                        <strong>Родительская категория</strong>
                    </p>
                    <br>
                    <a class="navbar-item has-text-dark" href="{{ route('category.show', ['id' => $category->parent->id]) }}">
                        <p>
                            <span class="icon">
                                <i class="fas fa-angle-left"></i>
                            </span>
                            {{ $category->parent->name }}
                        </p>
                    </a>
                    <br>
                @endif
                
                @if (count($category->children) > 0)
                    <p>
                        <strong>Подкатегории</strong>
                    </p>
                    <br>
                    @foreach ($category->children as $child)
                        <a class="navbar-item has-text-dark" href="{{ route('category.show', ['id' => $child->id]) }}">
                            <p>
                                <span class="icon">
                                    <i class="fas fa-angle-right"></i>
                                </span>
                                {{ $child->name }}
                            </p>
                        </a>
                    @endforeach
                @endif
            </div>
            <div class="column is-two-third">
                <p>
                    <strong>Товары</strong>
                </p>
                <br>
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
