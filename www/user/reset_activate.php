<?php 

require_once('../../lib/bootstrap.php');

function generatePassword() {
  global $appConfig;
  $password = '';
  for ($i = 0; $i < $appConfig['generated_password_length']; $i++) {
    $class = rand(1, 3);
    switch($class) {
      case 1: $password .= chr(ord('0') + rand(0, 9)); break;
      case 2: $password .= chr(ord('a') + rand(0, 25)); break;
      case 3: $password .= chr(ord('A') + rand(0, 25)); break;
    }
  }
  return $password;
}

$error = null;
$generatedPassword = null;

if (!isset($_REQUEST['username']) || !isset($_REQUEST['token'])) {
  $error = 'Invalid password reset request.';
}
else {
  $username = trim($_REQUEST['username']);
  $token = trim($_REQUEST['token']);

  $user = $em->getRepository('\rubikscomplex\model\User')->findOneBy(array('username' => $username));

  if ($user !== null) {
    $passwordEmails = $user->getPasswordResetEmails();
    foreach($passwordEmails as $passwordEmail) {
      if (!$passwordEmail->getUsed() && strcmp($passwordEmail->getToken(), $token) == 0) {
        $generatedPassword = generatePassword();
        $user->setPasswordhash($ph->HashPassword($generatedPassword));
        $passwordEmail->setUsed(true);
        $em->flush();
      }
    }
    if ($generatedPassword === null) {
      $error = 'Invalid password reset request.';
    }
  }
  else {
    $error = 'Invalid password reset request.';
  }
}

?>

<?php

$pageTitle = 'Password Reset';
$pathPrefix = '../';

include('../../template/header.php');

?>

<h1>PASSWORD RESET</h1>

<?php if ($error !== null) : ?>
<p class="error"><?php echo $error ?></p>
<?php else : ?>
<p>Your new password is <b><?php echo $generatedPassword ?></b>.  Return <a href="/" title="home">home</a>.</p>
<?php endif ?>

<?php

include('../../template/footer.php');
?>
