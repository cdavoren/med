<?php header('Content-Type: application/json; charset=utf-8') ?>
{
  "exists" : <?php

if (!isset($_REQUEST['username'])) {
    print 'false';
}
else {
    // This is really a horrible security hole (hence sleep), but convenient for users
    sleep(2);
    require_once('../../lib/bootstrap.php');

    $username = trim($_REQUEST['username']);
    $exists = $em->getRepository('\rubikscomplex\model\User')->findOneBy(array('username' => $username)) !== null;
    print $exists ? 'true' : 'false';
}

?>

}
