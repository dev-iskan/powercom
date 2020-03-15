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
</body>
<script>
    function toggleSidebar() {
        const sidebar = document.querySelector(".sidebar");
        console.log(sidebar);
        sidebar.classList.toggle('hidden');
    }
</script>
<script src="//code.jivosite.com/widget/KnjwLUQ9KJ" async></script>
@yield('script')
</html>
