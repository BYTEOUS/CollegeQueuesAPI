<?php

abstract class DatabaseModel {
    abstract public function getTableName();
    abstract public function getFields();
    public function getRows() { return []; }

    // Migrating model to database
    public static function migrate($model) {
        // Generate SQL request
        $sql = 'CREATE TABLE ' . $model->getTableName() . ' (';
        
        // Fields (columns)
        $fields = $model->getFields();
        $i = 0;
        $length = count($fields);
        foreach($fields as &$field) {
            // Field name, Field type & length
            $sql .= $field['name'] . ' ' . $field['type'] . ' (' . strval($field['length']) . ') ';
            if($field['type'] == 'INT') {  $sql .= 'UNSIGNED '; }
            // Auto increment
            if(array_key_exists('auto_increment', $field) && $field['auto_increment'] == true) {
                $sql .= 'AUTO_INCREMENT ';
            }
            // Primary
            if(array_key_exists('primary', $field) && $field['primary'] == true) {
                $sql .= 'PRIMARY KEY ';
            }
            // End
            if($length > 1 && $i < $length - 1) { $sql .= ','; }

            $i++;
        }
        $sql .= ');';

        // Call database
        Database::execute($sql);

        // Rows (Optinal)
        $rows = $model->getRows();
        if(count($rows) > 0) {
            foreach($rows as &$row) {
                DatabaseModel::insert($model, $row);
            }
        }
    }

    public static function find($model, array $where) {
        $sql = '';



        echo $sql;
    }
    public static function insert($model, array $values) {
        $sql = 'INSERT INTO ' . $model->getTableName() . ' (';
        $length = count($values);

        // Which columns
        $i = 0;
        foreach(array_keys($values) as &$colName) {
            $sql .= $colName;
            if($length > 1 && $i < $length - 1) { $sql .= ','; }
            $i++;
        }
        $sql .= ') VALUES (';

        // Which values
        $i = 0;
        foreach(array_values($values) as &$colValue) {
            if(is_string($colValue)) {
                $sql .= "'" . $colValue . "'";
            }
            else {
                $sql .= $colValue;
            }

            if($length > 1 && $i < $length - 1) { $sql .= ','; }
            $i++;
        }
        $sql .= ');';   

        echo $sql;

        Database::execute($sql);
    }
    public static function update($model, array $values, array $where) {
        
    }
    public static function delete($model, array $where) {
        
    }
}