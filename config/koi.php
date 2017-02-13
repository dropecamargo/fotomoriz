<?php

return [
    'name' => 'KOI Tecnologías de la Información S.A.S.',
    'nickname' => 'KOI-TI',
    'site' => 'http://www.koi-ti.com',
    'image' => '/images/koi.png',

    'app' => [
        'name' => 'Fotomoriz',
        'site' => 'http://fotomoriz.com//',
        'image' => [
            'logo' => '/images/logo.png',
            'avatar' => '/images/avatar.svg'
        ],
        'ano' => 2015
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
            'TI' => 'Tarjeta de Identidad',
            'CE' => 'Cédula de Extranjería',
            'PA' => 'Pasaporte', 'NI' => 'Nit'
        ],

        'regimen' => [
            1 => 'Simplificado',
            2 => 'Común',
            3 => 'Especial'
        ],

        'persona' => [
            'N' => 'Natural',
            'J' => 'Jurídica'
        ],

        'niif' => [
            '1' => 'Plena',
            '2' => 'Pymes',
            '3' => 'Micro pymes'
        ]
    ],

    'direcciones' => [
        'nomenclatura' => [
            'AC' => 'Avenida Calle',
            'AK' => 'Avenida Carrera',
            'CL' => 'Calle',
            'KR' => 'Carrera',
            'DG' => 'Diagonal',
            'TV' => 'Transversal'
        ],
        'alfabeto' => [
            'A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D', 'E' => 'E', 'F' => 'F', 'G' => 'G', 'H' => 'H', 'I' => 'I',
            'J' => 'J', 'K' => 'K', 'L' => 'L', 'M' => 'M', 'N' => 'N', 'O' => 'O', 'P' => 'P', 'Q' => 'Q', 'R' => 'R',
            'S' => 'S', 'T' => 'T', 'U' => 'U', 'V' => 'V', 'W' => 'W', 'X' => 'X', 'Y' => 'Y', 'Z' => 'Z'
        ],
        'cardinales' => [
            'SUR' => 'Sur',
            'ESTE' => 'Este',
            'NORTE' => 'Norte'
        ],
        'complementos' => [
            'APTO' => 'Apartamento',
            'AGR' => 'Agrupación',
            'CN' => 'Camino',
            'CART' => 'Carretera',
            'BLQ' => 'Bloque',
            'BG' => 'Bodega',
            'INT' => 'Interior',
            'KM' => 'Kilómetro',
            'LT' => 'Lote',
            'MZ' => 'Manzana',
            'MOD' => 'Módulo',
            'MPAL' => 'Municipal',
            'CS' => 'Casa',
            'OF' => 'Oficina',
            'CASER' => 'Caserío',
            'PARC' => 'Parcela',
            'P' => 'Piso',
            'CC' => 'Centro Comercial',
            'PLZ' => 'Plaza',
            'CIR' => 'Circunvalar',
            'PORT' => 'Portería',
            'COMUNID' => 'Comunidad',
            'PTO' => 'Puerto',
            'CONJ' => 'Conjunto',
            'RESG' => 'Resguardo',
            'CONS' => 'Consultorio',
            'RUR' => 'Rural',
            'CORREG' => 'Corregimiento',
            'SECT' => 'Sector',
            'DPTAL' => 'Departamental',
            'SUPERMZ' => 'Supermanzana',
            'ED' => 'Edificio',
            'TV' => 'Transversal',
            'TRR' => 'Torre',
            'ESQ' => 'Esquina',
            'ESTAC' => 'Estación',
            'ET' => 'Etapa',
            'VDA' => 'Vereda',
            'HAD' => 'Hacienda',
            'UNID' => 'Unidad',
            'UR' => 'Unidad resicencial',
            'URB' => 'Urbanización',
            'Vía' => 'VIA',
            'ZN' => 'Zona'
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
    ]
];