<?php

use Illuminate\Database\Seeder;
use App\Models\Base\Modulo;

class ModulosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Modulo::create([
        	'name' => 'administracion',
        	'display_name' => 'Administracion',
        	'nivel1' => 1
    	]);

        Modulo::create([
            'name' => 'cartera',
            'display_name' => 'Cartera',
            'nivel1' => 2
        ]);

    	Modulo::create([
        	'name' => 'contabilidad',
        	'display_name' => 'Contabilidad',
        	'nivel1' => 3
    	]);

    	Modulo::create([
        	'name' => 'inventario',
        	'display_name' => 'Inventario',
        	'nivel1' => 4
    	]);

        // Administracion
        Modulo::create([
            'display_name' => 'Modulos',
            'nivel1' => 1,
            'nivel2' => 1
        ]);

        Modulo::create([
            'display_name' => 'Referencias',
            'nivel1' => 1,
            'nivel2' => 2
        ]);

        //Modulos
        Modulo::create([
            'name' => 'tercerosinterno',
            'display_name' => 'Terceros internos',
            'nivel1' => 1,
            'nivel2' => 1,
            'nivel3' => 1
        ]);

        Modulo::create([
            'name' => 'roles',
            'display_name' => 'Roles',
            'nivel1' => 1,
            'nivel2' => 1,
            'nivel3' => 2
        ]);

        //Referencias
        Modulo::create([
            'name' => 'permisos',
            'display_name' => 'Permisos',
            'nivel1' => 1,
            'nivel2' => 2,
            'nivel3' => 1
        ]);

        Modulo::create([
            'name' => 'modulos',
            'display_name' => 'Modulos',
            'nivel1' => 1,
            'nivel2' => 2,
            'nivel3' => 2
        ]);

        // Cartera
        Modulo::create([
            'display_name' => 'Modulos',
            'nivel1' => 2,
            'nivel2' => 1
        ]);

        Modulo::create([
            'display_name' => 'Reportes',
            'nivel1' => 2,
            'nivel2' => 2
        ]);

        Modulo::create([
            'display_name' => 'Documentación',
            'nivel1' => 2,
            'nivel2' => 3
        ]);

        //Modulos
        Modulo::create([
            'name' => 'amortizaciones',
            'display_name' => 'Amortizaciones',
            'nivel1' => 2,
            'nivel2' => 1,
            'nivel3' => 1
        ]);

        Modulo::create([
            'name' => 'generarintereses',
            'display_name' => 'Generar intereses',
            'nivel1' => 2,
            'nivel2' => 1,
            'nivel3' => 2
        ]);

        Modulo::create([
            'name' => 'enviarintereses',
            'display_name' => 'Enviar intereses',
            'nivel1' => 2,
            'nivel2' => 1,
            'nivel3' => 3
        ]);

        // Reportes
        Modulo::create([
            'name' => 'reporteedades',
            'display_name' => 'Edades de cartera',
            'nivel1' => 2,
            'nivel2' => 2,
            'nivel3' => 1
        ]);

        Modulo::create([
            'name' => 'reporteposfechados',
            'display_name' => 'Cheques posfechados',
            'nivel1' => 2,
            'nivel2' => 2,
            'nivel3' => 2
        ]);

        Modulo::create([
            'name' => 'rintereses',
            'display_name' => 'Intereses generados',
            'nivel1' => 2,
            'nivel2' => 2,
            'nivel3' => 3
        ]);

        Modulo::create([
            'name' => 'reporterecibos',
            'display_name' => 'Recibos de caja',
            'nivel1' => 2,
            'nivel2' => 2,
            'nivel3' => 4
        ]);

        Modulo::create([
            'name' => 'reporteresumencobro',
            'display_name' => 'Resumen de cobro',
            'nivel1' => 2,
            'nivel2' => 2,
            'nivel3' => 5
        ]);

        // Documentacion
        Modulo::create([
            'name' => 'reporteverextractos',
            'display_name' => 'Ver extractos',
            'nivel1' => 2,
            'nivel2' => 3,
            'nivel3' => 1
        ]);

        // Contabilidad
        Modulo::create([
            'display_name' => 'Reportes',
            'nivel1' => 3,
            'nivel2' => 1
        ]);

        // Reportes
        Modulo::create([
            'name' => 'reportearp',
            'display_name' => 'Gastos ARP',
            'nivel1' => 3,
            'nivel2' => 1,
            'nivel3' => 1
        ]);

        // Inventario
        Modulo::create([
            'display_name' => 'Reportes',
            'nivel1' => 4,
            'nivel2' => 1
        ]);

        // Reportes
        Modulo::create([
            'name' => 'reporteanalisisinventario',
            'display_name' => 'Análisis inventario',
            'nivel1' => 4,
            'nivel2' => 1,
            'nivel3' => 1
        ]);

        Modulo::create([
            'name' => 'reporteentradassalidas',
            'display_name' => 'Entradas y salidas',
            'nivel1' => 4,
            'nivel2' => 1,
            'nivel3' => 2
        ]);
    }
}
