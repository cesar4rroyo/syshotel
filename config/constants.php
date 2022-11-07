<?php


return [
    'conceptTypes' => [
        'I' => 'Ingreso',
        'E' => 'Egreso',
    ],
    'roomStatus' => [
        'D' => 'Disponible',
        'O' => 'Ocupado',
        'M' => 'Mantenimiento',
        'R' => 'Reservado',
    ],
    'roomStatusColor' => [
        'Disponible' => 'bg-green-success',
        'Ocupado' => 'bg-red-500',
        'Mantenimiento' => 'bg-yellow-corp',
        'Reservado' => 'bg-blue-500',
    ],
    'bookingStatus' => [
        'P' => 'Pendiente',
        'C' => 'Confirmado',
        'A' => 'Anulado',
    ],
    'processStatus' => [
        'P' => 'Pendiente',
        'C' => 'Confirmado',
        'A' => 'Anulado',
    ],
    'checkin_hour' => 14,
    'checkin_minute' => 0,
    'checkout_hour' => 12,
    'checkout_minute' => 0,
];