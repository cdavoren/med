<?php
header('Content-Type: application/json; charset=utf-8');

require_once('../../lib/init.php');
$em = App::getManager();
$config = App::getConfiguration();

use \rubikscomplex\util\UUID;

$result = array(
    'success' => false,
    'error' => null,
);
$text = null;

$username = isset($_REQUEST['username']) ? trim($_REQUEST['username']) : null;


if ($username === null) {
  $result['error'] = 'No user specified';
}
else {
  $user = $em->getRepository('rubikscomplex\model\User')->findOneBy(array('username'=>$username));
  if ($user === null) {
    $result['error'] = 'Specified user does not exist';
  }
  else {
    $token = UUID::v4();

    $passwordEmail = new \rubikscomplex\model\UserPasswordEmail();
    $em->persist($passwordEmail);
    $passwordEmail->setUser($user);
    $passwordEmail->setEmail($user->getEmail());
    $now = new DateTime('now');
    $expiry = $now->add(new DateInterval('P1D'));
    $passwordEmail->setSentTime($now);
    $passwordEmail->setExpiryTime($expiry);
    $passwordEmail->setToken($token);
    $em->flush();

    $text = 
'Dear '.$user->getFullname().',

A password reset has been requested for your account on ubuntu-vm.  If this was not requested by you, please ignore this email.

Use this link to reset your password: http://ubuntu-vm/user/reset_activate.php?username='.$user->getUsername().'&token='.urlencode($token).'

This link will expire in 24 hours.
';

    $mailResult = mail(
      $to = $user->getEmail(),
      $subject = 'Password Reset',
      $message = $text,
      $additional_headers = 'From: '.$config['admin_email']
    );

    if ($mailResult !== true) {
      $result['error'] = 'Error sending email to user.';
    }
    else {
      $result['success'] = true;
    }
  }
}

echo json_encode($result);
?>
