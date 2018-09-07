<!DOCTYPE html>
<html>
  <head>
    <title>@yield('title', 'Sample App')-初学Laravel</title>
    <link rel="stylesheet" href="/css/app.css">
  </head>
  <body>
    @include('layouts/_header')
    @yield('content')
    @include('layouts/_footer')
  </body>
</html>