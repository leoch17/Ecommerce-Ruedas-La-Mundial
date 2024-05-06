<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Correo Electrónico de Pedido</title>
</head>

<body style="font-family: Arial, Helvetica, sans-serif; font-size: 16px;">

    @if ($mailData['userType'] == 'customer')
        <h1>¡¡Gracias por su pedido!!</h1>
        <h2>Su Id de pedido es: #{{ $mailData['order']->id }}</h2>
    @else
        <h1>Ha recibido un pedido: </h1>
        <h2>Id de pedido es: #{{ $mailData['order']->id }}</h2>
    @endif


    <h1>Dirección de Envío</h1>
    <address>
        <strong>{{ $mailData['order']->first_name . ' ' . $mailData['order']->last_name }}</strong><br>
        {{ $mailData['order']->address }}<br>
        {{ $mailData['order']->city }}, {{ $mailData['order']->zip }}
        {{ getStateInfo($mailData['order']->state_id)->name }}<br>
        Teléfono: {{ $mailData['order']->mobile }}<br>
        Correo Electrónico: {{ $mailData['order']->email }}
    </address>

    <h2>Productos</h2>

    <table cellpadding="3" cellspacing="3" border="0" width="700">
        <thead>
            <tr style="background: #CCC">
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mailData['order']->items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>${{ number_format($item->price, 2) }}</td>
                    <td align="center">{{ $item->qty }}</td>
                    <td>${{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="3" align="right">Subtotal:</th>
                <td>${{ number_format($mailData['order']->subtotal, 2) }}</td>
            </tr>

            <tr>
                <th colspan="3" align="right">Descuento:
                    {{ !empty($mailData['order']->coupon_code) ? '(' . $mailData['order']->coupon_code . ')' : '' }}
                </th>
                <td>${{ number_format($mailData['order']->discount, 2) }}</td>
            </tr>

            <tr>
                <th colspan="3" align="right">Envío:</th>
                <td>${{ number_format($mailData['order']->shipping, 2) }}</td>
            </tr>

            <tr>
                <th colspan="3" align="right">Total:</th>
                <td>${{ number_format($mailData['order']->grand_total, 2) }}</td>
            </tr>
        </tbody>
    </table>

</body>

</html>
