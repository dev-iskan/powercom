<nav class="navbar is-spaced" role="navigation" aria-label="main navigation" style="margin-bottom: 0;">
    <div class="navbar-brand">
        <a class="navbar-item" href="{{ url('main') }}">
            <img src="{{ URL::asset('/image/logo.svg') }}" alt="Powercom.uz">
        </a>
        
        <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbar" onclick="toggleSidebar()">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </a>
    </div>

    <div id="navbar" class="navbar-menu">
        <div class="navbar-end">
            <div class="buttons">
                <a href="{{ url('cart') }}" class="button is-white">
                    <span class="icon has-text-primary">
                        <i class="fas fa-shopping-cart"></i>
                    </span>
                </a>
                <a href="{{ route('login') }}" class="button is-white">
                    <span class="icon has-text-primary">
                        <i class="fas fa-user-circle"></i>
                    </span>
                </a>
            </div>
        </div>
    </div>
</nav>
<hr style="margin: 0;"/>
<nav id="catigories" class="navbar level box is-paddingless is-radiusless is-marginless">
    @foreach ($categories as $category)
        <div class="level-item">
            <div class="navbar-item has-dropdown is-hoverable is-mega" style="height: 100%;">
            <a href="{{ route('category', ['id' => $category->id]) }}" class="navbar-item has-text-black">{{ $category->name }}</a>
                @if (count($category->children))
                <div class="navbar-dropdown">
                    <div class="container" style="padding: 1em;">
                        <div class="columns is-multiline">
                            <div class="column is-full">
                                <h1 class="title is-6">{{ $category->name }}</h1>
                            </div>
                            @foreach ($category->children as $child)
                                <a class="navbar-item column is-one-third" href="{{ route('category', ['id' => $child->id]) }}">{{ $child->name }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    @endforeach
</nav>
<div class="sidebar hidden has-background-white p-20">
    <div class="has-text-right">
        <button class="button is-white" onclick="toggleSidebar()">
            <span class="icon has-text-grey">
                <i class="fas fa-times"></i>
            </span>
        </button>
    </div>
    <aside class="menu">
        <p class="menu-label">
            Общая
        </p>
        <ul class="menu-list">
            <li><a href="{{ route('main') }}">Главная</a></li>
            <li><a href="{{ route('cart') }}">Корзина</a></li>
            <li><a href="{{ route('login') }}">Личный кабинет</a></li>
        </ul>
        <p class="menu-label">
            Категории
        </p>
        <ul class="menu-list">
            @foreach ($categories as $category)
                @if (count($category->children))
                    <li>
                        <a href="{{ route('category', ['id' => $category->id]) }}">{{ $category->name }}</a>
                        <ul>
                            @foreach($category->children as $child)                                
                                <li><a href="{{ route('category', ['id' => $child->id]) }}">{{ $child->name }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                @else
                    <li><a href="{{ route('category', ['id' => $category->id]) }}">{{ $category->name }}</a></li>
                @endif
            @endforeach
        </ul>
      </aside>
</div>