<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Nota de Compra</title>
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
        <p>Nota Fiscal Eletrônica</p>
        <p>Cliente: {{ $usuario->nombre }} - {{ $usuario->email }}</p>
        <p>Data: {{ date('d/m/Y H:i') }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Produto</th>
                <th>Quantidade</th>
                <th>Preço</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($carrito as $item)
                <tr>
                    <td>{{ $item['nombre'] }}</td>
                    <td>{{ $item['cantidad'] }}</td>
                    <td>R$ {{ number_format($item['precio'], 2, ',', '.') }}</td>
                    <td>R$ {{ number_format($item['precio'] * $item['cantidad'], 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer totales">
        <p>Subtotal: R$ {{ number_format($subtotal, 2, ',', '.') }}</p>
        <p>Impostos (18%): R$ {{ number_format($igv, 2, ',', '.') }}</p>
        <p><strong>Total: R$ {{ number_format($total, 2, ',', '.') }}</strong></p>
    </div>
</body>
</html>