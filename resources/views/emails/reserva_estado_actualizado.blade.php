<!DOCTYPE html>
<html>
<head>
    <title>Actualización de Estado de la Reserva</title>
</head>
<body>
    <h1>Actualización de Estado de la Reserva</h1>
    <p>Estimado cliente,</p>
    <p>Le informamos que el estado de su reserva ha sido actualizado.</p>
    
    <p><strong>Fecha de la Reserva:</strong> {{ $reserva->Fecha_Reserva }}</p>
    <p><strong>Hora:</strong> {{ $reserva->Hora_Fin }}</p>
    <p><strong>Estado de la Reserva:</strong> 
        @if($estado == 0)
            Pendiente
        @elseif($estado == 1)
            Confirmada
        @elseif($estado == 2)
            Cancelada
        @endif
    </p>

    <p>Gracias por utilizar nuestro sistema de reservas.</p>
</body>
</html>
