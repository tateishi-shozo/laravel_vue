<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
 
<head>
    <meta charset="UTF-8">
    <title>SPAサンプル</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
 
<body>
 
    <div id="app">
        <book-component></book-component>
    </div>
 
    <script src="{{ mix('js/app.js') }}"></script>
</body>
 
</html>