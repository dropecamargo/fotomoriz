<?php

return [
    'name' => 'KOI Tecnologías de la Información S.A.S.',
    'nickname' => 'KOI-TI',
    'site' => 'http://www.koi-ti.com',
    'image' => '/images/koi.png',

    'app' => [
        'name' => 'Fotomoriz',
        'site' => 'http://www.fotomoriz.com',
        'image' => [
            'logo' => '/images/logo.png',
            'avatar' => '/images/avatar.svg'
        ],
        'ano' => 2006
    ],

    'meses' => [
        1 => 'Enero',
        2 => 'Febrero',
        3 => 'Marzo',
        4 => 'Abril',
        5 => 'Mayo',
        6 => 'Junio',
        7 => 'Julio',
        8 => 'Agosto',
        9 => 'Septiembre',
        10 => 'Octubre',
        11 => 'Noviembre',
        12 => 'Diciembre'
    ],

    'terceros' => [
        'tipo' => [
            'CC' => 'Cédula de Ciudadanía',
            'NI' => 'Nit',
            'XX' => 'Valide!!'
        ],

        'regimen' => [
            1 => 'Simplificado',
            2 => 'Común'
        ],

        'persona' => [
            'N' => 'Natural',
            'J' => 'Jurídica'
        ]
    ],

    'contabilidad' => [
        'plancuentas' => [
            'naturaleza' => [
                'D' => 'Débito',
                'C' => 'Crédito'
            ],
            'tipo' => [
                'N' => 'Ninguno',
                'I' => 'Inventario',
                'C' => 'Cartera',
                'P' => 'Cuentas por pagar'
            ],
            'niveles' => [
                '1' => 'Uno',
                '2' => 'Dos',
                '3' => 'Tres',
                '4' => 'Cuatro',
                '5' => 'Cinco',
                '6' => 'Seis',
                '7' => 'Siete',
                '8' => 'Ocho'
            ]
        ],

        'centrocosto' => [
            'tipo' => [
                'N' => 'Ninguno',
                'O' => 'Orden',
                'I' => 'Inventario'
            ]
        ],

        'documento' => [
            'consecutivo' => [
                'A' => 'Automático',
                'M' => 'Manual'
            ]
        ],

        'iva' => [
            '16' => '16%',
            '19' => '19%'
        ]
    ],

    'produccion' => [
        'formaspago' => [
            'CO' => 'Contado',
            'CT' => 'Crédito'
        ]
    ],

    'template' => [
        'bg' => 'bg-green'
    ]
];
