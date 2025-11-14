<?php
class UserModel {
    private $users = [
        ['id' => 1, 'nome' => 'Usuário A'],
        ['id' => 2, 'nome' => 'Usuário B']
    ];

    public function getAll() {
        return $this->users;
    }

    public function getById($id) {
        foreach ($this->users as $user) {
            if ($user['id'] == $id) {
                return $user;
            }
        }
        return null;
    }
}
