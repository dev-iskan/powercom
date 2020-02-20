<div class="container is-fluid has-background-light is-paddingless">
    <div class="columns is-vcentered">
        <div class="column has-text-right">
            <a href="{{ url('signin') }}" class="button is-light">
                Вход
            </a>
            <a href="{{ url('signup') }}" class="button is-light">
                Регистрация
            </a>
        </div>
    </div>
</div>
<nav class="navbar is-spaced" role="navigation" aria-label="main navigation">
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
</nav>
<hr style="margin: 0;"/>
<nav class="navbar level is-transparent" style="margin: 0;">
    <div class="level-item">
        <div class="navbar-item has-dropdown is-hoverable is-mega" style="height: 100%;">
            <a class="navbar-item has-text-black">Телефоны</a>
            <div class="navbar-dropdown">
                <div class="container is-fluid">
                    <div class="columns">
                        <div class="column">
                            <h1 class="title is-6 is-mega-menu-title">Sub Menu Title</h1>
                            <a class="navbar-item " href="/documentation/overview/start/">
                                Overview
                            </a>
                            <a class="navbar-item " href="http://bulma.io/documentation/modifiers/syntax/">
                                Modifiers
                            </a>
                            <a class="navbar-item " href="http://bulma.io/documentation/columns/basics/">
                                Columns
                            </a>
                            <a class="navbar-item " href="http://bulma.io/documentation/layout/container/">
                                Layout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>