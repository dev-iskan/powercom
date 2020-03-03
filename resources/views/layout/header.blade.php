<nav class="navbar is-spaced" role="navigation" aria-label="main navigation" style="margin-bottom: 0;">
    <div class="navbar-brand">
        <a class="navbar-item" href="{{ url('main') }}">
            <img src="{{ URL::asset('/image/logo.svg') }}" alt="Powercom.uz">
        </a>
        
        <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </a>
    </div>

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
