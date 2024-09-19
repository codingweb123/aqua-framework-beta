<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ PaperWork::returnVars($config)["PROJECT_NAME"] }}</title>
    <link rel="shortcut icon" href="{{ resource('public/img/logo.png') }}" type="image/x-icon" />
    <link rel="canonical" href="{{ resource() }}" />
    <link rel="stylesheet" href="{{ resource('public/css/main.css') }}" />
    <link rel="stylesheet" href="{{ resource('public/css/style.css') }}" />
</head>
<body>
    <div class="wrapper">
        <x-slot />
    </div>
    <script src="{{ resource('public/js/init.js') }}"></script>
</body>
</html>