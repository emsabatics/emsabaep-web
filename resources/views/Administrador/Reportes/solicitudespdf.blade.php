<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Solicitudes</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .logo {
            width: 200px;
        }
        .title {
            text-align: center;
            font-size: 18px;
            margin-bottom: 10px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('files-img/logo.png') }}" class="logo" alt="Logo">
        <div class="title">Reporte de Solicitudes</div>
        <div style="width: 120px;"></div> <!-- espacio para alinear -->
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 40px;">#</th>
                <th style="width: 100px;">Cuenta</th>
                <th style="width: 150px;">Nombres</th>
                <th style="width: 150px;">Email</th>
                <th style="width: 150px;">Teléfono</th>
                <th style="width: 100px;">Fecha Ingreso</th>
                <th style="width: 100px;">Estado</th>
                <th style="width: 150px;">Última Modificación</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mensajes as $index => $mensaje)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $mensaje['cuenta'] }}</td>
                    <td>{{ $mensaje['nombres'] }}</td>
                    <td>{{ $mensaje['email'] }}</td>
                    <td>{{ $mensaje['telefono'] }}</td>
                    <td>{{ $mensaje['ultima_modificacion'] }}</td>
                    <td>{{ $mensaje['estado_solicitud'] }}</td>
                    <td>{{ $mensaje['nombre_usuario'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>