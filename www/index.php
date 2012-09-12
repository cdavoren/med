<?php 
require_once('../lib/bootstrap.php');

$ur = $em->getRepository('\rubikscomplex\model\User');

$pageTitle = 'Index Page'; 
?>

<?php require_once('../template/header.php'); ?>

<h1>TEST</h1>
<p>This is body text in a &lt;p&gt; tag.</p>

<?php
if ($ur->findBy(array('username' => 'admin')) == null) {
  echo '<p>Admin user not found, creating...</p>';
  $adminUser = new User();
  $adminUser->setUsername('admin');
  $adminUser->setPasswordhash($ph->HashPassword('password'));
  $adminUser->setEmail('admin@rubikscomplex.net');
  $adminUser->setFullname('Chris Davoren');
  $em->persist($adminUser);
  $em->flush();
}
$users = $ur->findAll();
?>

<p>
User information:<br />

<?php if (count($users) > 0) : ?>
  <ul>
  <?php foreach($users as $user) : ?>
    <li><?php echo $user->getUsername() ?> (<?php echo $user->getFullname() ?>)</li>
  <?php endforeach ?>
  </ul>
<?php else : ?>
  No users found.<br />
<?php endif ?>

<?php require_once('../template/footer.php'); ?>
