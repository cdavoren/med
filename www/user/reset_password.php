<?php

require_once('../../lib/bootstrap.php');

$error = null;
$text = null;

if ($loggedUser == null) {
  $error = "No user is logged in";
}
else {
  $token = uniqid();

  $passwordEmail = new \rubikscomplex\model\UserPasswordEmail();
  $em->persist($passwordEmail);
  $passwordEmail->setUser($loggedUser);
  $passwordEmail->setEmail($loggedUser->getEmail());
  $now = new DateTime('now');
  $expiry = $now->add(new DateInterval('P1D'));
  $passwordEmail->setSentTime($now);
  $passwordEmail->setExpiryTime($expiry);
  $passwordEmail->setToken($token);
  $em->flush();

  $text = 
  'Dear '.$loggedUser->getFullname().',

  A password reset has been requested for your account on ubuntu-vm.  If this was not requested by you, please ignore this email.

  Use this link to reset your password: http://ubuntu-vm/user/reset_activate.php?username='.$loggedUser->getUsername().'&token='.$token.'
  ';

  $mailResult = mail(
    $to = $loggedUser->getEmail(),
    $subject = 'Password Reset',
    $message = $text,
    $additional_headers = 'From: admin@ubuntu-vm.com'
  );

  if (!$mailResult) {
    $error = 'Error sending email to user.';
  }
}

if ($error === null) {
  ?>{"success":true,"text":"<?php echo $text ?>"}<?php
}else {
  ?>{"success":false,"message":"<?php echo $error ?>"}<?php
}
?>
