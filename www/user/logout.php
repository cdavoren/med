<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Origin: '.(isset($_SERVER['HTTPS']) ? 'http' : 'https').'://ubuntu-vm');

require_once('../../lib/bootstrap.php');

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

echo json_encode($result);

?>
