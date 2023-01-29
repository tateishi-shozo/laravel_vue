<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
 
<head>
    <meta charset="UTF-8">
    <title>新規登録</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
 
<body>
    <div class="bg-light" id="register">
        <register-component></register-component>
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>