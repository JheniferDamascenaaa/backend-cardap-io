<?php

class RestaurantModel {
    public function getAll() {
        return [
            ["id" => 1, "nome" => "Restaurante A"],
            ["id" => 2, "nome" => "Restaurante B"]
        ];
    }

    public function getById($id) {
        return ["id" => $id, "nome" => "Restaurante $id"];
    }
}
