<?php
require_once('../../lib/init.php');
$config = App::getConfiguration();

$result = array(
    'success' => true,
    'error' => null,
);
if (isset($_SESSION['userid'])) {
  unset($_SESSION['userid']);
}
else {
    $result['success'] = false;
    $result['error'] = 'No user is currently logged in';
}

sleep(1);

if (isset($_REQUEST['cookie'])) {
  setcookie($_REQUEST['cookie'], json_encode($result), 0, '/', '', false, false);
  header('Content-Type: text/html');
  header('Connection: close');
}
else {
  header('Content-Type: application/json; charset=utf-8');
  header('Access-Control-Allow-Credentials: true');
  header('Access-Control-Allow-Origin: '.(isset($_SERVER['HTTPS']) ? 'http' : 'https').'://'.$config['app_server']);
}

echo json_encode($result);

?>
