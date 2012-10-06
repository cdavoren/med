<?php 
require_once('../lib/init.php');

$em = App::getManager();
$ph = App::getHasher();
$loggedUser = App::getUser();

$ur = $em->getRepository('\rubikscomplex\model\User');
$tgr = $em->getRepository('\rubikscomplex\model\TestGroup');
$tr = $em->getRepository('\rubikscomplex\model\Test');

$pageTitle = 'Index Page'; 
?>

<?php require_once('../template/header.php'); ?>

<script type="text/javascript">
function logout_handler(e) {
  $.ajax({
    type: 'post',
    dataType: 'json',
    url: 'user/logout.php',
    data: { username : '<?php echo $loggedUser === null ? 'null' : $loggedUser->getUsername() ?>' },
    success: function(d) {
      if (d.success) {
        // alert('Logout successful.');
        location.reload();
      }
      else {
        alert('An error occurred while logging out.  Please contact your system administrator.');
      }
    },
    error: function(jqXHR, reason, errorThrown) {
      console.log(reason);
      console.log(errorThrown);
      alert('An error occurred while logging out.  Please contact your system administrator.');
    }
  });
  return false;
}

$(document).ready(function() {
  $('a#logout').click(logout_handler);
});
</script>

<h1>TEST</h1>

<p>
<?php
if ($loggedUser !== null) {
  print 'The current user is '.$loggedUser->getFullname().'.';
?>
 ( <a id="logout" href="#" alt="logout" title="logout">logout</a> )
<?php
}
else {
  print 'There is no user logged in.';
}
?>
</p>

<?php
if ($ur->findBy(array('username' => 'admin')) == null) {
  echo '<p>Admin user not found, creating...</p>';
  $adminUser = new \rubikscomplex\model\User();
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
</p>

<p>
Test information:<br />

<ul>
<?php foreach($tgr->findAll() as $testGroup): ?>

  <li>
    <?php echo $testGroup->getDescription() ?> <i>(<?php echo $testGroup->getName() ?>)</i>
    <ul>
      <?php foreach($testGroup->getTestGroupings() as $tgr): ?>
        <li><a href="take_test.php?id=<?php echo $tgr->getTests()->getId() ?>&page=1" title="<?php echo $tgr->getTests()->getTitle() ?>"><?php echo $tgr->getTests()->getTitle() ?></a></li>
      <?php endforeach ?>
    </ul>
  </li>

<?php endforeach ?>
</ul>

</p>
<?php require_once('../template/footer.php'); ?>
