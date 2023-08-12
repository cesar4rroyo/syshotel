<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Main CRUDS Language Lines
    |--------------------------------------------------------------------------
    |
     */

    'general' => [
        'placeholder' => 'Buscar',
        'range' => 'Filas a mostrar',
        'new' => 'Nuevo',
        'add' => 'Agregar :entity',
        'edit' => 'Editar :entity',
        'delete' => 'Eliminar :entity',
        'import' => 'Importar :entity',
    ],

    'admin' => [
        'user' => [
            'search' => 'Usuario o Email',
            'name' => 'Usuario',
            'email' => 'Email',
            'password' => 'Contraseña',
            'person' => 'Persona',
            'usertype' => 'Tipo de Usuario',
            'title' => 'Usuario',
            'branch' => 'Sucursales',
            'cashbox' => 'Cajas',
        ],
        'menugroup' => [
            'name' => 'Nombre del Grupo de Menú',
            'order' => 'Orden',
            'icon' => 'Ícono',
            'title' => 'Grupo de Menú',
        ],
        'menuoption' => [
            'name' => 'Nombre de la Opción de Menú',
            'link' =>  'Link',
            'group' => 'Grupo de Menú',
            'order' => 'Orden',
            'icon' => 'Ícono',
            'title' => 'Opción de Menú',
        ],
        'role' => [
            'name' => 'Nombre del Rol',
            'title' => 'Rol',
        ],
        'usertype' => [
            'name' => 'Nombre del Tipo de Usuario',
            'title' => 'Tipo de Usuario',
        ],
        'access' => [
            'name' => 'Nombre del Acceso',
            'title' => 'Acceso',
        ],
        'setting' => [
            'title' => 'Configuraciones',
            'razonsocial' => 'Razón Social',
            'ruc' => 'RUC',
            'nombrecomercial' => 'Nombre Comercial',
            'direccion' => 'Dirección',
            'telefono' => 'Teléfono',
            'email' => 'Email',
            'logo' => 'Logo',
            'billing' => 'Facturación',
            'serie' => 'Serie',
            'igv' => 'IGV',
            'password_sunat' => 'Contraseña Sunat',
            'serverId' => 'Servidor',
        ],
        'business' => [
            'name' => 'Nombre',
            'status' => 'Estado',
            'address' => 'Dirección',
            'phone' => 'Teléfono',
            'email' => 'Email',
            'city' => 'Ciudad',
            'usertype' => 'Tipo de Usuario',
            'person' => 'Persona',
            'title' => 'Empresas',
            'settings' => 'Configuraciones de la Empresa',
            'branches' => 'Sucursales de la Empresa',
            'users' => 'Usuarios de la Empresa',
        ],
        'branch' => [
            'title' => 'Sucursales',
            'name' => 'Nombre',
            'status' => 'Estado',
            'address' => 'Dirección',
            'phone' => 'Teléfono',
            'email' => 'Email',
            'city' => 'Ciudad',
            'business' => 'Empresa',
            'title' => 'Sucursales',
            'settings' => 'Configuraciones de la Sucursal',
            'users' => 'Usuarios de la Sucursal',
            'isMain' => 'Estas seguro de cambiar de sucursal principal?',
        ],
        'floor' => [
            'title'  => 'Pisos',
            'name'   => 'Nombre',
            'branch' => 'Sucursal',
        ],
        'room' => [
            'title'    => 'Habitaciones',
            'name'     => 'Nombre',
            'number'   => 'Número',
            'status'   => 'Estado',
            'type'     => 'Tipo de Habitación',
            'floor'    => 'Piso',
            'branch'   => 'Sucursal',
            'business' => 'Empresa',
        ],
        'roomtype' => [
            'title'    => 'Tipo de Habitación',
            'name'     => 'Nombre',
            'capacity' => 'Capacidad',
            'price'    => 'Precio',
            'branch'   => 'Sucursal',
            'business' => 'Empresa',
            'price_hour' => 'Precio por Hora',
        ],
        'category' => [
            'title'    => 'Categorías',
            'name'     => 'Nombre',
            'branch'   => 'Sucursal',
            'business' => 'Empresa',
        ],
        'product' => [
            'title'    => 'Productos',
            'name'     => 'Nombre',
            'sale'     => 'Precio de venta',
            'purchase' => 'Precio de compra',
            'unit'     => 'Unidad',
            'category' => 'Categoría',
            'branch'   => 'Sucursal',
            'business' => 'Empresa',
            'add'     => 'Agregar Stocks',
            'move'    => 'Mover Stock',
        ],
        'service' => [
            'title'    => 'Servicios',
            'name'     => 'Nombre',
            'description' => 'Descripción',
            'price'    => 'Precio',
            'branch'   => 'Sucursal',
            'business' => 'Empresa',
        ],
        'concept' => [
            'title'    => 'Conceptos',
            'name'     => 'Nombre',
            'type'     => 'Tipo',
            'branch'   => 'Sucursal',
            'business' => 'Empresa',
        ],
        'unit' => [
            'title'    => 'Unidades',
            'name'     => 'Nombre',
            'description' => 'Descripción',
            'branch'   => 'Sucursal',
            'business' => 'Empresa',
        ],
        'cashbox' => [
            'title'    => 'Cajas',
            'name'     => 'Nombre',
            'branch'   => 'Sucursal',
            'business' => 'Empresa',
            'phone'    => 'Teléfono',
            'comments' => 'Comentarios',
        ],
        'payment' => [
            'title'    => 'Métodos de Pago',
            'name'     => 'Nombre',
            'branch'   => 'Sucursal',
            'business' => 'Empresa',
            'type'     => 'Tipo',
            'notes'    => 'Comentarios',
            'amount'   => 'Monto',
            'pos'      => 'P.O.S',
            'card'     => 'Tarjeta',
            'noperation' => 'Nro. Operación',
            'bank'     => 'Banco',
            'digitalwallet' => 'Billetera Digital',
        ],
        'cashregister' => [
            'title'    => 'Control Flujo de Caja',
            'name'     => 'Nombre',
            'branch'   => 'Sucursal',
            'business' => 'Empresa',
            'phone'    => 'Teléfono',
            'comments' => 'Comentarios',
            'search'   => 'Buscar',
            'type'     => 'Tipo',
        ],
        'people' => [
            'title'    => 'Personas',
            'name'     => 'Nombres / Razón Social',
            'lastname' => 'Apellido',
            'dni'      => 'DNI / RUC',
            'phone'    => 'Teléfono',
            'email'    => 'Email',
            'address'  => 'Dirección',
            'city'     => 'Ciudad',
            'country'  => 'País',
        ],
    ],

    'control' => [
        'cashregister' => [
            'number' => 'Número',
            'date' => 'Fecha',
            'client' => 'Cliente',
            'concept' => 'Concepto',
            'amount' => 'Monto',
            'notes' => 'Comentarios',
            'amountreal' => 'Monto Real',
        ],
        'management' => [
            'number' => 'Número',
            'date' => 'Fecha',
            'client' => 'Cliente',
            'start_date' => 'Fecha de Entrada',
            'end_date' => 'Fecha de Salida',
            'start_time' => 'Hora de Entrada',
            'end_time' => 'Hora de Salida',
            'hours' => 'Horas',
            'days' => 'Noches',
            'amount' => 'Total',
            'payment_type' => 'Método de Pago',
            'room' => 'Habitación',
            'booking_id' => 'Reserva Nro.',
            'notes' => 'Comentarios',
            'price' => 'Precio Habitación',
            'general' => 'Datos Generales',
            'billing' => 'Cobro y Facturación',
            'charge' => 'Cobrar',
            'paymentType' => 'Forma de Pago',
            'documentType' => 'Tipo de Documento',
            'documentNumber' => 'Número de Documento',
            'clientBilling' => 'Datos del Cliente',
        ],
        'billinglist' => [
            'title' => 'Lista de Comprobantes',
        ],
        'bookinglist' => [
            'title' => 'Lista de Reservas',
            'status' => 'Estados',
        ],
        'new' => 'Nuevo Movimiento',
        'open' => 'Aperturar Caja',
        'close' => 'Cerrar Caja',
        'printA4' => 'Imprimir A4',
        'printTicket' => 'Imprimir Ticket',
        'edit' => 'Editar Movimiento',
        'delete' => 'Eliminar Movimiento',
        'check-in' => 'Check-In',
        'check-out' => 'Check-Out',
        'printticket' => 'Imprimir Ticket',
        'printA4' => 'Imprimir A4',
    ],

    'utils' => [
        'new' => 'Nuevo',
        'cancel' => 'Cancelar',
        'detele-mesage' => '¿Está seguro de eliminar el registro?',
        'no-result' => 'No se encontraron resultados',
        'import'  => 'Importar',
        'file'   => 'Archivo',
        'back'   => 'Regresar',
        'search' => 'Buscar',
        'close'  => 'Cerrar',
        'change_status' => 'Estas seguro de cambiar el estado de la habitación?',
    ],

    'sell' => [
        'add' => 'Agregar',
        'pay' => 'Cobrar',
        'date' => 'Fecha',
        'number' => 'Número',
        'products' => [
            'title' => 'Venta de Productos',
            'name' => 'Nombre',
            'price' => 'Precio',
            'sale' => 'Precio de Venta',
            'purchase' => 'Precio de Compra',
            'quantity' => 'Cantidad',
            'subtotal' => 'Subtotal',
            'total' => 'Total',
            'actions' => 'Acciones',
            'remove' => 'Eliminar',
        ],
        'services' => [
            'title' => 'Venta de Servicios',
            'name' => 'Nombre',
            'price' => 'Precio',
            'sale' => 'Precio de Venta',
            'purchase' => 'Precio de Compra',
            'quantity' => 'Cantidad',
            'subtotal' => 'Subtotal',
            'total' => 'Total',
            'actions' => 'Acciones',
            'remove' => 'Eliminar',
        ],
    ],

    'bookings' => [
        'title' => 'Reservas',
        'number' => 'Número',
        'datefrom' => 'Fecha de Entrada',
        'dateto' => 'Fecha de Salida',
        'days' => 'Noches',
        'room' => 'Habitaciones Disponibles',
        'client' => 'Cliente',
        'notes' => 'Comentarios',
        'amount' => 'Pago Adelantado',
        'cancel' => 'Cancelar Reserva',
    ]

];