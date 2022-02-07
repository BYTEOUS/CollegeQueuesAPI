<?php
// Library
require __DIR__ . '/lib/Database.php';
require __DIR__ . '/lib/DatabaseModel.php';

// Models
require __DIR__ . '/models/UserModel.php';
require __DIR__ . '/models/OperationModel.php';
require __DIR__ . '/models/QueueModel.php';
//require __DIR__ . '/models/WindowModel.php';

// Connect to database
$connectResult = Database::tryConnect();
if($connectResult['status'] == 'fail') {
    echo 'Ошибка подключения к БД: ' . $connectResult['message'];
}
else {
    // Migrate all tables
    DatabaseModel::migrate(new UserModel());
    DatabaseModel::migrate(new OperationModel());
    DatabaseModel::migrate(new QueueModel());
    //DatabaseModel::migrate(new WindowModel());
}