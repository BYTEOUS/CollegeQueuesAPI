<?php

class OperationModel extends DatabaseModel {
    public function getTableName() {
        return 'operations';
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
                'name' => 'text',
                'type' => 'VARCHAR',
                'length' => 255
            )
        ];
    }
    public function getRows() {
        return [
            // id: 0
            array(
                'name' => 'Подать заявление'
            ),
            // id: 1
            array(
                'name' => 'Сдать оригинал'
            ),
            // id: 2
            array(
                'name' => 'Задать вопрос'
            ),
            // id: 3
            array(
                'name' => 'Подписать договора'
            ),
            // id: 4
            array(
                'name' => 'Прочие вопросы'
            )
        ];
    }
}