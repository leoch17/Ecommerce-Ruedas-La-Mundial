<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reiniciar Contraseña de Correo Electrónico</title>
</head>

<body style="font-family: Arial, Helvetica, sans-serif; font-size: 16px;">

    <p>Hola, {{ $formData['user']->name }}</p>

    <h1>Ha solicitado cambiar la contraseña:</h1>

    <p>Por favor, haga click en el siguiente enlace para restaurar la contraseña</p>

    <a href="{{ route('frontend.resetPassword', $formData['token']) }}">Click Aquí</a>

    <p>Gracias</p>

</body>

</html>
