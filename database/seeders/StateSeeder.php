<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = array(
			array('code' => 'VE-Z', 'name' => 'Amazonas'),
			array('code' => 'VE-B', 'name' => 'Anzoátegui'),
			array('code' => 'VE-C', 'name' => 'Apure'),
			array('code' => 'VE-D', 'name' => 'Aragua'),
			array('code' => 'VE-E', 'name' => 'Barinas'),
			array('code' => 'VE-F', 'name' => 'Bolívar'),
			array('code' => 'VE-G', 'name' => 'Carabobo'),
			array('code' => 'VE-H', 'name' => 'Cojedes'),
			array('code' => 'VE-Y', 'name' => 'Delta Amacuro'),
			array('code' => 'VE-W', 'name' => 'Dependencias Federales'),
			array('code' => 'VE-A', 'name' => 'Distrito Capital'),
			array('code' => 'VE-I', 'name' => 'Falcón'),
			array('code' => 'VE-J', 'name' => 'Guárico'),
			array('code' => 'VE-K', 'name' => 'Lara'),
            array('code' => 'VE-L', 'name' => 'Mérida'),
			array('code' => 'VE-M', 'name' => 'Miranda'),
			array('code' => 'VE-N', 'name' => 'Monagas'),
			array('code' => 'VE-O', 'name' => 'Nueva Esparta'),
			array('code' => 'VE-P', 'name' => 'Portuguesa'),
			array('code' => 'VE-R', 'name' => 'Sucre'),
			array('code' => 'VE-S', 'name' => 'Táchira'),
            array('code' => 'VE-T', 'name' => 'Trujillo'),
            array('code' => 'VE-X', 'name' => 'Vargas'),
			array('code' => 'VE-U', 'name' => 'Yaracuy'),
			array('code' => 'VE-V', 'name' => 'Zulia'),
		);

		DB::table('states')->insert($states);
    }
}
