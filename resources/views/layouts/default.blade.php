<!DOCTYPE html>
<html>
  <head>
    <title>@yield('title', 'Sample App')-初学Laravel</title>
    <link rel="stylesheet" href="/css/app.css">
    <script src="/js/app.js"></script>
  </head>
  <body>
    @include('layouts._header')
    @include('shared._message')
    @yield('content')
    @include('layouts._footer')
  </body>
</html>