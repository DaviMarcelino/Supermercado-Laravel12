<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Nota de Compra - Supermercado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #3b82f6;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            color: #3b82f6;
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
            background-color: #3b82f6;
            color: white;
        }
        .footer {
            text-align: right;
            margin-top: 20px;
            padding: 15px;
            background-color: #f8fafc;
            border-radius: 5px;
        }
        .totales p {
            margin: 5px 0;
        }
        .grand-total {
            font-size: 14px;
            font-weight: bold;
            color: #3b82f6;
        }
        .customer-info {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f1f5f9;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🛒 Supermercado</h1>
        <p>Nota Fiscal Eletrônica</p>
    </div>

    <div class="customer-info">
        <p><strong>Cliente:</strong> {{ $usuario->name ?? $usuario->nome ?? 'Cliente' }}</p>
        <p><strong>Email:</strong> {{ $usuario->email ?? 'cliente@email.com' }}</p>
        <p><strong>Data:</strong> {{ date('d/m/Y H:i') }}</p>
        <p><strong>Número do Pedido:</strong> #{{ rand(1000, 9999) }}-{{ date('Ymd') }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Produto</th>
                <th>Quantidade</th>
                <th>Preço Unitário</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($carrinho as $item)
                <tr>
                    <td>{{ $item['nome'] }}</td>
                    <td>{{ $item['quantidade'] }}</td>
                    <td>R$ {{ number_format($item['preco'], 2, ',', '.') }}</td>
                    <td>R$ {{ number_format($item['preco'] * $item['quantidade'], 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer totales">
        <p><strong>Subtotal:</strong> R$ {{ number_format($subtotal, 2, ',', '.') }}</p>
        <p><strong>Impostos (18%):</strong> R$ {{ number_format($igv, 2, ',', '.') }}</p>
        <p class="grand-total"><strong>TOTAL:</strong> R$ {{ number_format($total, 2, ',', '.') }}</p>
    </div>
</body>
</html>