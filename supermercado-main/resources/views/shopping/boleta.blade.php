<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Boleta de Compra</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .footer {
            text-align: right;
        }
        .totales p {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Supermercado</h1>
        <p>Boleta Electrónica</p>
        <p>Cliente: {{ $usuario->nombre }} - {{ $usuario->email }}</p>
        <p>Fecha: {{ date('d/m/Y H:i') }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($carrito as $item)
                <tr>
                    <td>{{ $item['nombre'] }}</td>
                    <td>{{ $item['cantidad'] }}</td>
                    <td>S/ {{ number_format($item['precio'], 2) }}</td>
                    <td>S/ {{ number_format($item['precio'] * $item['cantidad'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer totales">
        <p>Subtotal: S/ {{ number_format($subtotal, 2) }}</p>
        <p>IGV (18%): S/ {{ number_format($igv, 2) }}</p>
        <p><strong>Total: S/ {{ number_format($total, 2) }}</strong></p>
    </div>
</body>
</html>
