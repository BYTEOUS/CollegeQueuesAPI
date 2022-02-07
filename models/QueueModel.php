<?php

class QueueModel extends DatabaseModel {
    public function getTableName() {
        return 'queues';
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
                'name' => 'operation',
                'type' => 'JSON',
                'length' => 255
            ),
            array(
                'name' => 'clients',
                'type' => 'JSON',
                'length' => 1000

                // Array of:
                // {
                //    "number": "INT",
                //    "added": "DATE",
                //    "operation": "INT"
                // }
            )
        ];
    }
}