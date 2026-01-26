<!DOCTYPE html>
<html>
<head>
    <title>Ficha de Personal</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #333; margin-bottom: 20px; padding-bottom: 10px; }
        .section-title { background: #f2f2f2; padding: 5px; font-weight: bold; margin-top: 15px; border-left: 5px solid #007bff; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #777; }
    </style>
</head>
<body>
    <div class="header">
        <h2>FICHA TÉCNICA DEL TRABAJADOR</h2>
        <p>Institución de Salud - Sistema de Gestión</p>
    </div>

    <div class="section-title">DATOS PERSONALES</div>
    <table>
        <tr><th>Nombres:</th><td>{{ $persona->nombres }}</td></tr>
        <tr><th>Apellidos:</th><td>{{ $persona->apellidos }}</td></tr>
        <tr><th>Nacionalidad:</th><td>{{ $persona->nacionalidad }}</td></tr>
        <tr><th>Fecha de Nacimiento:</th><td>{{ $persona->fecha_nacimiento }}</td></tr>
        <tr><th>Género:</th><td>{{ $persona->genero }}</td></tr>
        <tr><th>Teléfono:</th><td>{{ $persona->telefono }}</td></tr>
        <tr><th>Dirección:</th><td>{{ $persona->direccion }}</td></tr>
    </table>

    <div class="section-title">INFORMACIÓN LABORAL</div>
    <table>
        <tr><th>Cargo / Rol:</th><td><strong>{{ $persona->tipo_trabajador }}</strong></td></tr>
        <tr><th>Modalidad de Ítem:</th><td>{{ $persona->item }}</td></tr>
        <tr><th>Cuenta Vinculada:</th><td>{{ $persona->usuario->nombre ?? 'Sin cuenta' }}</td></tr>
    </table>

    <div class="footer">
        Documento generado el {{ date('d/m/Y H:i') }} - Sistema de Recursos Humanos
    </div>
</body>
</html>