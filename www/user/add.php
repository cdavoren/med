<?php

require_once('../../lib/bootstrap.php');

$pageTitle = 'Registration';
$pathPrefix = '../';

function validate() {
  global $em;
  $error = null;

  $username = trim($_POST['username']);
  $password = trim($_POST['password']);
  $password2 = trim($_POST['password2']);
  $email = trim($_POST['email']);
  $fullname = trim($_POST['fullname']);

  if (strcmp($password, $password2) !== 0) {
    $error = 'Password fields must be identical.';
  }
  else if (strlen($username) == 0) {
    $error = 'Username is required.';
  }
  else if (strlen($password) == 0) {
    $error = 'Password is required.';
  }
  else if (strlen($email) == 0) {
    $error = 'Email is required.';
  }
  else if (strlen($fullname) == 0) {
    $error = 'Full name is required.';
  }
  else if (count($em->getRepository('\rubikscomplex\model\User')->findBy(array('username' => $username))) != 0) {
    $error = 'That username is already taken, please choose another.';
  }

  return $error;
}



?>

<?php require('../../template/header.php') ?>

<?php

$error = null;
if (isset($_POST['username'])) {
  $error = validate();
  if ($error === null) {
    $newUser = new \rubikscomplex\model\User();
    $newUser->setUsername(trim($_POST['username']));
    $newUser->setPasswordhash($ph->HashPassword(trim($_POST['password'])));
    $newUser->setEmail(trim($_POST['email']));
    $newUser->setFullname(trim($_POST['fullname']));
    $em->persist($newUser);
    $em->flush();
  }
}

$username = isset($_POST['username']) ? $_POST['username'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$fullname = isset($_POST['fullname']) ? $_POST['fullname'] : '';

?>

<?php if ($error !== null || !isset($_POST['username'])) : ?>
<h1>REGISTRATION</h1>

<?php
  if ($error !== null) {
?>
  <p class="error">Error: <?php echo $error ?></p>
<?php
  }
?>
<p>Enter your details below:</p>
<form action="add.php" method="post">
<table class="inputform">
<tr>
<td>Username:</td><td><input name="username" type="text" maxlength="60" value="<?php echo $username ?>"/></td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr>
  <td>Password:</td><td><input name="password" type="password" maxlength="60" /></td>
</tr>
<tr>
  <td>Re-enter password:</td><td><input name="password2" type="password" maxlength="60" /></td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr>
<td>Email:</td><td><input name="email" type="text" maxlength="255" value="<?php echo $email ?>"/></td>
</tr>
<tr>
<td>Full name:</td><td><input name="fullname" type="text" maxlength="255" value="<?php echo $fullname ?>"/></td>
</tr>
</table>
<input type="submit" name="submit" value="Add" />
</form>
<?php else: ?>

<h1>REGISTRATION SUCCESSFUL</h1>
<p>User '<?php echo trim($username) ?>' has been successfully registered.</p>

<?php endif ?>

<?php require('../../template/footer.php') ?>
