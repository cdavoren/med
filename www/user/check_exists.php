<?php 
header('Content-Type: application/json; charset=utf-8');

if (!isset($_REQUEST['username'])) {
    print '{"exists":null,"error":"No user specified"}';
}
else {
    // This is really a horrible security hole (hence sleep), but convenient for users
    sleep(1);
    require_once('../../lib/bootstrap.php');

    $username = trim($_REQUEST['username']);
    $exists = $em->getRepository('\rubikscomplex\model\User')->findOneBy(array('username' => $username)) !== null;
    printf('{"exists":%s,"error":null}', $exists ? 'true' : 'false');
}

?>
