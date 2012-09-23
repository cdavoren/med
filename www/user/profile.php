<?php require_once('../../lib/init.php'); ?>
<?php

$pageTitle = 'Edit Profile';
require('../../template/header.php');

$em = App::getManager();
$loggedUser = App::getUser();
$ph = App::getHasher();

function validate() {
  global $loggedUser, $ph;

  $error = null;
  $email = trim($_REQUEST['email']);
  $fullname = trim($_REQUEST['fullname']);

  $oldpassword = trim($_REQUEST['oldpassword']);
  $newpassword = trim($_REQUEST['newpassword']);
  $newpassword2 = trim($_REQUEST['newpassword2']);

  if (strlen($email) == 0) {
    $error = 'Email is required.';
  }
  else if (strlen($fullname) == 0) {
    $error = 'Full name is required.';
  }
  else if (strlen($oldpassword) != 0) {
    if (strlen($newpassword) == 0) {
      $error = 'New password cannot be blank if entering new password.';
    }
    else if (strcmp($newpassword, $newpassword2) != 0) {
      $error = 'New password fields are not identical.';
    }
    else if (!$ph->CheckPassword($oldpassword, $loggedUser->getPasswordhash())) {
      $error = 'Entered password is incorrect.';
    }
  }
  return $error;
}

?>

<h1>EDIT MY PROFILE</h1>

<?php
if ($loggedUser === null) {
?>
<p>No user is currently logged in.</p>
<?php
}
else {
  $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : $loggedUser->getEmail();
  $fullname = isset($_REQUEST['fullname']) ? $_REQUEST['fullname'] : $loggedUser->getFullname();

  $error = null;
  if (isset($_REQUEST['email'])) {
    $error = validate();

    if ($error === null) {
      $loggedUser->setEmail(trim($email));
      $loggedUser->setFullname(trim($fullname));
      $oldpassword = trim($_REQUEST['oldpassword']);
      $newpassword = trim($_REQUEST['newpassword']);
      if (strlen($oldpassword) > 0) {
        $loggedUser->setPasswordhash($ph->HashPassword(trim($_REQUEST['newpassword'])));
      }
      $em->flush();
    }
  }
  if ($error !== null) {
?>
  <p class="error"><?php echo $error ?></p>
<?php
  }
  else if (isset($_REQUEST['email'])) {
?>
  <p class="success">Details successfully updated.</p>
<?php
  }
?>
<p>Update your details:</p>
<form action="profile.php" method="post">
<table class="inputform">
<tr>
<td>Email:</td><td><input name="email" type="text" size="60" value="<?php echo $email ?>" /></td>
</tr>
<tr>
<td>Full name:</td><td><input name="fullname" type="text" size="60" value="<?php echo $fullname ?>" /></td>
</tr>
</table>
<p>&nbsp;</p>
<p>Change password:</p>
<table class="inputform">
<tr>
<td>Old password:</td><td><input name="oldpassword" type="password" size="60" value="" /></td>
</tr>
<tr>
<td>New password:</td><td><input name="newpassword" type="password" size="60" value="" /></td>
</tr>
<tr>
<td>Re-enter new password:</td><td><input name="newpassword2" type="password" size="60" value="" /></td>
</tr>
</table>
<input name="submit" type="submit" class="custombutton" value="Update my profile" />
</form>

<?php
}
require('../../template/footer.php');
?>
