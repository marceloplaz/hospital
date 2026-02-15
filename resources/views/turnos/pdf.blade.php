<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Turnos</title>
    <style>
        @page { margin: 1cm; }
        body { font-family: 'Helvetica', sans-serif; font-size: 10px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #28a745; padding-bottom: 10px; }
        .titulo { font-size: 18px; font-weight: bold; color: #28a745; margin: 0; }
        .subtitulo { font-size: 12px; color: #666; margin: 5px 0; }
        
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th { background-color: #28a745; color: white; padding: 8px; border: 1px solid #1e7e34; }
        td { border: 1px solid #ddd; padding: 6px; text-align: center; vertical-align: middle; word-wrap: break-word; }
        
        .medico-nombre { text-align: left; font-weight: bold; background-color: #f8f9fa; width: 120px; }
        .turno-item { background: #e9ecef; border-radius: 4px; padding: 3px; margin-bottom: 2px; font-size: 8px; display: block; border: 1px solid #ced4da; }
        .horas { font-weight: bold; color: #007bff; }
    </style>
</head>
<body>

    <div class="header">
    <h1 class="titulo">ROL DE TURNOS: {{ strtoupper($servicio->nombre) }}</h1>
    <p class="subtitulo">
        {{-- Aquí mostramos el rango del mes completo --}}
        Periodo: {{ $fechaInicioMes }} al {{ $fechaFinMes }} - {{ $semana->mes->nombre }} {{ $anio }}
    </p>
    
</div>

    <table>
        <thead>
            <tr>
                <th style="width: 150px;">MÉDICO</th>
                <th>LUN</th>
                <th>MAR</th>
                <th>MIÉ</th>
                <th>JUE</th>
                <th>VIE</th>
                <th>SÁB</th>
                <th>DOM</th>
                <th style="width: 40px;">HRS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $u)
                <tr>
                    <td class="medico-nombre">{{ $u->nombre }}</td>
                    @foreach(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $dia)
                        <td>
                            @php $asignaciones = $u->turnosAsignados->where('dia', $dia); @endphp
                            @foreach($asignaciones as $asig)
                                <div class="turno-item">
                                    <strong>{{ $asig->turnoDetalle->nombre_turno }}</strong><br>
                                    {{ date('H:i', strtotime($asig->turnoDetalle->hora_inicio)) }}-{{ date('H:i', strtotime($asig->turnoDetalle->hora_fin)) }}
                                </div>
                            @endforeach
                        </td>
                    @endforeach
                    <td class="horas">
                        {{ $u->turnosAsignados->sum(fn($a) => $a->turnoDetalle->duracion_horas ?? 0) }}h
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Al final de tu archivo pdf.blade.php, después de la tabla --}}

<div style="margin-top: 50px;">
    <table style="border: none; width: 100%;">
        <tr>
            <td style="border: none; text-align: left; width: 50%;">
                <p style="margin-bottom: 50px;">___________________________</p>
                
                <strong>Generado por:</strong> {{ $generadoPor ?? 'Usuario no identificado' }}<br>
                
                <small>Personal Administrativo / Jefe de Servicio</small>
            </td>
            
        </tr>
    </table>
</div>
    <div style="margin-top: 30px; font-size: 8px; text-align: right; color: #999;">
        Generado el: {{ date('d/m/Y H:i:s') }} jugadordeunbit---Marplaz
    </div>

</body>
</html>