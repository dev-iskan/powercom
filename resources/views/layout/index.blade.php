<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @yield('meta')
    <title>Powercom.uz</title>
    <link rel="shortcut icon" href="{{ URL::asset('favicon.ico') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('/css/app.css') }}">
    <script src="{{ URL::asset('/js/app.js') }}"></script>
</head>
<body>
@include('layout.header')
@yield('body')
@include('layout.footer')
<div class="contacts">
    <a href="https://t.me/joinchat/AZSyoRvwhnQjS2NcR2MAjA" class="button is-link is-rounded is-large contact">
        <span class="icon is-large">
            <i class="fab fa-telegram-plane"></i>
        </span>
    </a>
    <a href="https://chat.whatsapp.com/KJFd5X9OeOe6J0vmTJMaOA" class="button is-success is-rounded is-large contact">
        <span class="icon is-large">
            <i class="fab fa-whatsapp"></i>
        </span>
    </a>
</div>
</body>
<script>
    function toggleSidebar() {
        const sidebar = document.querySelector(".sidebar");
        sidebar.classList.toggle('hidden');
    }
</script>
<script src="//code.jivosite.com/widget/KnjwLUQ9KJ" async></script>
@yield('script')
</html>
