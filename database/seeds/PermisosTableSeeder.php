<?php

use Illuminate\Database\Seeder;
use App\Models\Base\Permiso;

class PermisosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permiso::create([
            'name'   => 'consultar',
            'display_name'   => 'Consultar',
            'description'   => 'Permisos de consulta',
        ]);

        Permiso::create([
            'name'   => 'crear',
            'display_name'   => 'Crear',
            'description'   => 'Permisos de creacion',
        ]);

        Permiso::create([
            'name'   => 'editar',
            'display_name'   => 'Editar',
            'description'   => 'Permisos de editar',
        ]);

        Permiso::create([
            'name'   => 'eliminar',
            'display_name'   => 'Eliminar',
            'description'   => 'Permisos para eliminar',
        ]);

        Permiso::create([
            'name'   => 'opcional1',
            'display_name'   => 'Opcional 1',
            'description'   => 'Permisos opcionales',
        ]);

        Permiso::create([
            'name'   => 'opcional2',
            'display_name'   => 'Opcional 2',
            'description'   => 'Permisos opcionales',
        ]);

        Permiso::create([
            'name'   => 'opcional3',
            'display_name'   => 'Opcional 3',
            'description'   => 'Permisos opcionales',
        ]);
    }
}
