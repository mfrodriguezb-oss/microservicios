<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class RetroItem {


    public static function crear($data) {
        return Capsule::table('retro_items')->insert([
            'sprint_id' => $data['sprint_id'],
            'categoria' => $data['categoria'],
            'descripcion' => $data['descripcion'],
            'cumplida' => $data['cumplida'] ?? null,
            'fecha_revision' => $data['fecha_revision'] ?? null
        ]);
    }


    public static function obtenerTodos() {
        return Capsule::table('retro_items')->get();
    }

   
    public static function obtenerPorSprint($sprint_id) {
        return Capsule::table('retro_items')
            ->where('sprint_id', $sprint_id)
            ->get();
    }

}
