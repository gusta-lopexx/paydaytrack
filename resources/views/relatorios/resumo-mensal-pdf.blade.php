<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Resumo Mensal</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background: #f2f2f2; text-align: left; }
        td.num { text-align: right; }
    </style>
</head>
<body>
    <h2>Resumo Mensal</h2>

    <table>
        <thead>
            <tr>
                <th>Mês/Ano</th>
                <th>Entradas</th>
                <th>Saídas</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr>
                    <td>{{ $row->mes }}</td>
                    <td class="num">R$ {{ number_format($row->entradas, 2, ',', '.') }}</td>
                    <td class="num">R$ {{ number_format($row->saidas, 2, ',', '.') }}</td>
                    <td class="num">R$ {{ number_format($row->saldo, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
