<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Origin: '.(isset($_SERVER['HTTPS']) ? 'http' : 'https').'://ubuntu-vm');

require_once('../../lib/bootstrap.php');

$result = array(
    'success' => false,
    'error' => null,
);

if (isset($_SESSION['userid'])) {
  unset($_SESSION['userid']);
}

if (isset($_REQUEST['username']) && isset($_REQUEST['password'])) {
  $username = trim($_REQUEST['username']);
  $password = trim($_REQUEST['password']);
  $user = $em->getRepository('\rubikscomplex\model\User')->findOneBy(array('username' => $username));
  if ($user !== null) {
    $result['success'] = $ph->CheckPassword($password, $user->getPasswordhash());
    if ($result['success']) {
      $_SESSION['userid'] = $user->getId();
    }
    else {
      $result['error'] = 'Incorrect username or password';
    }
  }
  else {
    $result['error'] = 'Incorrect username or password';
  }
}

sleep(2);

echo json_encode($result);

?>
