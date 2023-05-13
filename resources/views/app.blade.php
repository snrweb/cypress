<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/js/app.js'])
</head>
<style>
    html,
    body {
        height: 100%;
        margin: 0;
    }

    #app {
        height: 100%;
        margin: 0;
    }
</style>

<body>
    <div id="app">
        <main-app></main-app>
    </div>
</body>

</html>
