<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class Sprint {

    public static function obtenerTodos() {
        return Capsule::table('sprints')->get();
    }

    public static function crear($data) {
        return Capsule::table('sprints')->insert([
            'nombre' => $data['nombre'],
            'fecha_inicio' => $data['fecha_inicio'],
            'fecha_fin' => $data['fecha_fin']
        ]);
    }

}