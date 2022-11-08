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
    ],
    'roomStatusColor' => [
        'Disponible' => 'bg-green-success',
        'Ocupado' => 'bg-red-500',
        'Mantenimiento' => 'bg-yellow-corp',
    ],
    'roomStatusIcon' => [
        'Disponible' => 'fas fa-check-circle',
        'Ocupado' => 'fas fa-sign-out-alt',
        'Mantenimiento' => 'fas fa-hand-sparkles',
    ],
    'roomTextStatus' => [
        'Disponible' => ' Check-In',
        'Ocupado' => ' Check-Out',
        'Mantenimiento' => ' Cambiar Estado',
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
    'paymentType' => [
        'E' => 'Efectivo',
        'T' => 'Tarjeta',
        'D' => 'Depósito',
        'C' => 'Crédito',
    ],
];