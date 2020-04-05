<footer class="footer has-background-light">
    <div class="container">
        <div class="columns is-multiline">
            <div class="column">
                <p>
                    <strong>Информация</strong>
                </p>
                <br>
                <a class="has-text-dark" href="{{ route('main') }}">Главная</a>
                <br>
                <a class="has-text-dark" href="{{ Auth::check() ? route('home') : route('login') }}">
                    {{ Auth::check() ? 'Персональный кабинет' : 'Вход' }}
                </a>
                <br>
                <a class="has-text-dark" href="{{ route('show_register') }}">Регистрация</a>
                <br>
                <a class="has-text-dark" href="{{ route('about') }}">О компании</a>
                <br>
                <a class="has-text-dark" href="{{ route('public-offer') }}">Публичная оферта</a>
            </div>
            <div class="column has-text-right-desktop">
                <p>
                    <strong>Контакты</strong>
                </p>
                <br>
                <p>
                    <span class="icon">
                        <i class="fas fa-envelope"></i>
                    </span>
                    info@powercom.uz
                </p>
                <p>
                    <span class="icon">
                        <i class="fas fa-phone"></i>
                    </span>
                    +99871 207-21-21
                </p>
                <p>
                    <span class="icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </span>
                    Узбекистан, г. Ташкент, ул. Олой, 1
                </p>
            </div>
            <div class="column is-full">
                <hr class="has-background-grey-lighter">
            </div>
            <div class="column">
                <p>
                    © 2020 ITU-Uniservices. Все права защищены.
                </p>
            </div>
            <div class="column has-text-right-desktop">
                <a href="https://t.me/joinchat/AZSyoRvwhnQjS2NcR2MAjA" class="has-text-dark">
                    <span class="icon is-medium">
                        <i class="fab fa-telegram"></i>
                    </span>
                </a>
                <a href="https://chat.whatsapp.com/KJFd5X9OeOe6J0vmTJMaOA" class="has-text-dark">
                    <span class="icon is-medium">
                        <i class="fab fa-whatsapp"></i>
                    </span>
                </a>
                <a class="has-text-dark">
                    <span class="icon is-medium">
                        <i class="fab fa-facebook"></i>
                    </span>
                </a>
            </div>
        </div>
    </div>
</footer>
