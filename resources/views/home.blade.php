<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    <h1>Home</h1>
    @auth
        <p>Bienvenido estas autenticado</p>    
    @endauth
    @guest
        <p>para ver el contenido <a href="/login">inicia sesion</a></p>    
    @endguest
</body>
</html>