<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Correo Electrónico de Contacto</title>
</head>

<body style="font-family: Arial, Helvetica, sans-serif; font-size: 16px;">

    <h1>Ha recibido un correo electrónico de contacto</h1>

    <p>Nombre Completo: {{ $mailData['name'] }}</p>
    <p>Correo Electrónico: {{ $mailData['email'] }} </p>
    <p>Asunto: {{ $mailData['subject'] }} </p>

    <p>Mensaje:</p>
    <p>{{ $mailData['message'] }}</p>

</body>

</html>
