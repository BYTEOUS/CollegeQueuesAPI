<?php

class UserModel extends DatabaseModel {
    public function getTableName() {
        return 'users';
    }
    public function getFields() {
        return [
            array(
                'name' => 'id',
                'type' => 'INT',
                'length' => 5,

                'primary' => true,
                'auto_increment' => true
            ),
            array(
                'name' => 'login',
                'type' => 'VARCHAR',
                'length' => 50
            ),
            array(
                'name' => 'password',
                'type' => 'VARCHAR',
                'length' => 255
            )
        ];
    }
}