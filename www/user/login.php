<?php
require_once('../../lib/init.php');
$config = App::getConfiguration();
$em = App::getManager();
$ph = App::getHasher();

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

if (isset($_REQUEST['cookie'])) {
  setcookie($_REQUEST['cookie'], json_encode($result), 0, '/', '', false, false);
  header('Content-Type: text/html');
  header('Connection: close');
  /*
  header('Cache-Control:');
  header('Pragma:');
   */
}
else {
  // header('Content-Type: application/json; charset=utf-8');
  header('Content-Type: text/html; charset=utf-8');
  header('Access-Control-Allow-Credentials: true');
  header('Access-Control-Allow-Origin: '.(isset($_SERVER['HTTPS']) ? 'http' : 'https').'://'.$config['app_server']);
  echo json_encode($result);
}

?>
