<?php
// Library ----------------------------------
require __DIR__ . '/lib/Router.php';
require __DIR__ . '/lib/Request.php';
require __DIR__ . '/lib/Database.php';
require __DIR__ . '/lib/DatabaseModel.php';
require __DIR__ . '/lib/Auth.php';
require __DIR__ . '/lib/Mode.php';
// Modes ------------------------------------
require __DIR__ . '/modes/InfoMode.php';
require __DIR__ . '/modes/WorkerMode.php';
require __DIR__ . '/modes/TerminalMode.php';
$modes = [ new InfoMode(), new WorkerMode(), new TerminalMode() ];

// Check database connection
if(!Database::connect()) {
    Request::finishErrorServer('Ошибка подключения к БД');
    return; 
}

// Base API
$api = new Router();
$api->set404(function() {
    header('HTTP/1.1 404 Not Found');
});
// Auth -------------------------------------
$api->post('api/auth', function() {
    Request::begin();

    // Check fields
    if(Request::requireFields(['login','password'])) {
        $fields = Request::getFields();

        // Check credentials
        $user = Database::getUser($fields['login'], $fields['password']);
        if($user && $user['password'] == $fields['password']) {
            $headers = array('alg' =>'HS256','typ'=>'JWT');
            $payload = array('role'=>$user['role_id']);

            $jwt = Auth::generateJwt($headers, $payload);

            // Return JWT
            Request::finishSuccessData(array(
                'user_token' => $jwt
            ));
        }
        else {
            Request::finishError('Authentication failed', 401);
        }
    }
});
$api->get('api/logout', function() {
    Request::begin();

    Request::finishSuccessData(array(
        'message' => 'logout'
    ));
});
// Modes ----------------------------------
foreach ($modes as &$mode) {
    echo $mode->baseRoute;

    Request::begin();
    Request::requireMode($mode->name);
    foreach($mode->endpoints as &$endpoint) {
        $url = 'api/' . $mode->baseRoute . '/' . $endpoint->route;
        switch($endpoint['method']) {
            case 'GET':
                $api->get($url, $endpoint['handler']);
                break;
            case 'POST':
                $api->post($url, $endpoint['handler']);
                break;
            case 'PATCH':
                $api->patch($url, $endpoint['handler']);
                break;
            case 'DELETE':
                $api->delete($url, $endpoint['handler']);
                break;
            default: 
                Request::finishErrorServer('Неизвестный метод: ' . $endpoint['method']);
                break;
        }
    }
}
$api->get('api/user', function() {
    Request::begin();

    if(Request::requireRole(1)) {
        $users = Database::getAllUsers();

        // Format
        foreach($users as &$user) {
            // ID as int
            $user['id'] = intval($user['id']);

            // Group
            if($user['role_id'] == '1') { $user['group'] = 'Администратор'; }
            if($user['role_id'] == '2') { $user['group'] = 'Официант'; }
            if($user['role_id'] == '3') { $user['group'] = 'Повар'; }
            unset($user['role_id']);
        }

        Request::finishSuccessData($users);
    }
});
$api->run();