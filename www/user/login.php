<?php
require_once('../../lib/bootstrap.php');

$success = false;
$error = null;

if (isset($_SESSION['userid'])) {
  unset($_SESSION['userid']);
}

if (isset($_REQUEST['username']) && isset($_REQUEST['password'])) {
  $username = trim($_REQUEST['username']);
  $password = trim($_REQUEST['password']);

  $user = $em->getRepository('\rubikscomplex\model\User')->findOneBy(array('username' => $username));

  if ($user !== null) {
    $success = $ph->CheckPassword($password, $user->getPasswordhash());

    if ($success) {
      $_SESSION['userid'] = $user->getId();
    }
    else {
      $error = 'Unknown username or password';
    }
  }
  else {
    $error = 'Unknown username or password';
  }
}

?>
<?php header('Content-Type: application/json; charset=utf-8') ?>
{"success" : <?php echo $success ? 'true' : 'false' ?>,"error":<?php echo $error === null ? 'null' : '"'.$error.'"' ?>}
