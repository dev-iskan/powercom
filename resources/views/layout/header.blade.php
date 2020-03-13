<nav class="navbar is-spaced" role="navigation" aria-label="main navigation" style="margin-bottom: 0;">
    <div class="navbar-brand">
        <a class="navbar-item" href="{{ route('main') }}">
            <img src="{{ URL::asset('/image/logo.svg') }}" alt="Powercom.uz">
        </a>

        <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbar"
           onclick="toggleSidebar()">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </a>
    </div>

    <div id="navbar" class="navbar-menu">
        <div class="navbar-end">
            <div class="buttons">
                <a href="{{ route('cart.index') }}" class="button is-white">
                    <span class="icon has-text-primary">
                        <i class="fas fa-shopping-cart"></i>
                    </span>
                </a>
                <a href="{{ Auth::check() ? route('home') : route('login') }}" class="button is-white">
                    <span class="icon has-text-primary">
                        @if(Auth::check())
                            <i class="fas fa-user-circle"></i>
                        @else
                            <i class="fas fa-sign-in-alt"></i>
                        @endif
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
                <a href="{{ route('products.index', ['categories' => $category->id]) }}"
                   class="navbar-item has-text-black">{{ $category->name }}</a>
                @if (count($category->children))
                    <div class="navbar-dropdown">
                        <div class="container" style="padding: 1em;">
                            <div class="columns is-multiline">
                                <div class="column is-full">
                                    <h1 class="title is-6">{{ $category->name }}</h1>
                                </div>
                                @foreach ($category->children as $child)
                                    <a class="navbar-item column is-one-third"
                                       href="{{ route('products.index', ['categories' => $child->id]) }}">{{ $child->name }}</a>
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
            <li><a href="{{ route('cart.index') }}">Корзина</a></li>
            <li><a href="{{ Auth::check() ? route('home') : route('login') }}">Личный кабинет</a></li>
        </ul>
        <p class="menu-label">
            Категории
        </p>
        <ul class="menu-list">
            @foreach ($categories as $category)
                @if (count($category->children))
                    <li>
                        <a href="{{ route('products.index', ['categories' => $category->id]) }}">{{ $category->name }}</a>
                        <ul>
                            @foreach($category->children as $child)
                                <li><a href="{{ route('products.index', ['categories' => $child->id]) }}">{{ $child->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @else
                    <li><a href="{{ route('products.index', ['categories' => $category->id]) }}">{{ $category->name }}</a></li>
                @endif
            @endforeach
        </ul>
    </aside>
</div>

@if(session()->get('message'))
<div class="modal is-active" id="modal">
    <div class="modal-background" style="opacity: 0.5"></div>
    <div class="modal-content">
        <div class="box p-10">
            <header class="modal-card-head has-background-white">
                <p class="modal-card-title is-size-6">Сообщение</p>
                <button class="delete" aria-label="close" onclick="closeModal()"></button>
            </header>
            <section class="modal-card-body" style="max-width: 400px">
                <p>{{ session()->get('message') }}</p>
            </section>
        </div>
    </div>
  </div>

  @section('script')
    <script>
        function closeModal() {
            const modal = document.getElementById('modal');
            modal.classList.remove('is-active');
        }
    </script>
  @endsection
@endif