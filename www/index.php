<?php 
require_once('../lib/init.php');

$em = App::getManager();
$ph = App::getHasher();
$loggedUser = App::getUser();

$ur = $em->getRepository('\rubikscomplex\model\User');
$tgr = $em->getRepository('\rubikscomplex\model\TestGroup');
$tr = $em->getRepository('\rubikscomplex\model\Test');

$pageTitle = 'Index Page'; 

function getTestByIdentifier($identifier) {
  global $tr;
  return $tr->findOneBy(array('identifier' => $identifier));
}

function getTestsByIdentifiers($identifierList) {
  $results = array();
  foreach ($identifierList as $identifier) {
    $results[] = getTestByIdentifier($identifier);
  }
  return $results;
}

function makeTestLink($test) {
  $link = sprintf('<a href="take_test.php?id=%d&page=1" alt="%s" title="%s">%s</a>', $test->getId(), $test->getTitle(), $test->getTitle(), $test->getTitle());
  return $link;
}

function makeTestLinksByGroup($groupName, $titleMatch=null) {
  global $tgr;
  $testGroup = $tgr->findOneBy(array('name' => $groupName));
  $links = array();
  foreach ($testGroup->getTestGroupings() as $tgg) {
    if ($titleMatch !== null) {
      if (!preg_match($titleMatch, $tgg->getTests()->getTitle())) {
        continue;
      }
    }
    $links[] = makeTestLink($tgg->getTests());
  }
  return $links;
}

$sem89Blocks = array(
  'Cardiology, Respiratory and General Medicine',
  'Neurosurgery, Neurology, ENT and Ophthalmology',
  'Nephrology, Urology, Vascular Surgery and Endocrinology',
  'Orthopaedics, Rheumatology and Dermatology',
  'Oncology, Haematology and Infectious Diseases',
  'Gastroenterology, Hepatobiliary and Colorectal Surgery');

$preclinFullNames = array(
  'CRL' => 'Cardiac, Respiratory and Locomotor',
  'CSGD' => 'Control Systems, Growth & Development',
  'DMF' => 'Defense Mechanisms and Their Failure',
  'HP3' => 'Health Practice 3',
  'HP4' => 'Health Practice 4',
  'HP5' => 'Health Practice 5');
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

<div>
  <!-- 6 columns (half-page) -->
  <div style="float: left; width: 460px; margin-right: 20px;">
    <br />
    <h1 style="border: none; margin-bottom: 0px; font-size: 60px;">Test your <span class="highlight">medicine</span>.</h1>
    <br />
    <p style="font-size: 14px;">University of Melbourne online tests and resources to help you study.</p>
  </div>
  <!-- 6 columns (half-page) -->
  <div style="float: left;">
    <img src="images/vitruvian_man_whiteborder.png" alt="Vitruvian Man" title="Vitruvian Man" />
  </div>
    <div style="clear: both;"></div>
</div>

<br />
<br />

<div>
<!-- 4 columns, (1/3 width) -->
<div style="float: left; width: 300px; margin-right: 20px;">
  <!-- <div style="border: 1px solid #666; border-radius: 4px; padding: 5px;"> -->
  <div>
    <h2>Browse.</h2> 
    <p style="font-size: larger;">
      <br />
      Take a leisurely look through past exams, including full solutions and explanations where available.  This is a great way to orient yourself with the material and get an idea of the required level of knowledge.
      <br />
      <br />
      <br />
      <br />
    </p>
  </div>
</div>
<div style="float: left; width: 300px; margin-right: 20px;">
  <h2>Test.</h2>
  <p style="font-size: larger;">
  <br />
  When you're ready, take the tests online.  Results are instant; marking is just a click of a button away.  Longer tests are broken up into small chunks for faster feedback and ultimately smoother learning.
  </p>
</div>
<div style="float: left; width: 300px;">
  <h2>Retest.</h2>
  <p style="font-size: larger;">
  <br />
  We all know that repetition is the source of all learning, so come back and take the tests again as often as you like!  Over time, you'll be able to identify those pesky gaps in your knowledge, and focus on patching them up.
  </p>
</div>
<div style="clear: both;"></div>
</div>

<br />

<div style="text-align: center;">
<p>
<span style="font-size: 42px;">Get started straight away!</span><br />
<br />
<span style="font-size: larger;">Browse the tests below...</span>
</p>
</div>

<h1>CLINICALS</h2>

<div>
<div style="float: left; width: 140px; margin-right: 20px; text-align: center;">
<img src="images/sem89.png" alt="Semesters 8&9" title="Semesters 8&9" />
</div>
<div style="float: left; width: 780px;">
<h2>Semesters 8 &amp; 9 - Hospital Specialty Rotations</h2>
<p>
<br />
<?php echo makeTestLink(getTestByIdentifier('eb3e898e-5f08-4256-a386-cd21fcb0ac7a')) ?><br />
<?php echo makeTestLink(getTestByIdentifier('df047808-9fa9-4a24-8344-c72647ffecbf')) ?><br />
<?php echo makeTestLink(getTestByIdentifier('cf6466c0-ff35-4f0f-b99e-6fd33dbc07b6')) ?><br />
<?php echo makeTestLink(getTestByIdentifier('4f5583d0-2ba4-4a85-ac29-123b48650c68')) ?><br />
</p>
<br />
<b><i>Blocks:</i></b>
<div>
  <img style="float: left; margin-right: 20px;" src="images/sem89_cardioresp.png" alt="<?php echo $sem89Blocks[0] ?>" title="<?php echo $sem89Blocks[0] ?>" />
  <h3>1 - <? echo $sem89Blocks[0] ?></h3>
  <?php echo makeTestLink(getTestByIdentifier('1db9f179-13e4-4995-8b34-2a7aa61b7c51')) ?>
  <div style="clear: both;"></div>
  <br />
</div>
<div>
  <img style="float: left; margin-right: 20px;" src="images/sem89_neuro_dim.png" alt="<?php echo $sem89Blocks[1] ?>" title="<?php echo $sem89Blocks[1] ?>" />
  <h3>2 - <?echo $sem89Blocks[1] ?></h3>
  <?php echo makeTestLink(getTestByIdentifier('96cb29fb-a9fe-40b5-9ed4-4622edd2d889')) ?>
  <div style="clear: both;"></div>
  <br />
</div>
<div>
  <img style="float: left; margin-right: 20px;" src="images/sem89_endo.png" alt="<?php echo $sem89Blocks[2] ?>" title="<?php echo $sem89Blocks[2] ?>" />
  <h3>3 - <?echo $sem89Blocks[2] ?></h3>
  <?php echo makeTestLink(getTestByIdentifier('aeb042fd-826d-4560-8874-3a8e6a29bc73')) ?>
  <div style="clear: both;"></div>
  <br />
  </div>
<div>
  <img style="float: left; margin-right: 20px;" src="images/sem89_ortho.png" alt="<?php echo $sem89Blocks[3] ?>" title="<?php echo $sem89Blocks[3] ?>" />
  <h3>4 - <?echo $sem89Blocks[3] ?></h3>
  <?php echo makeTestLink(getTestByIdentifier('4b19c5a3-8615-455b-8696-3d21ff3eb444')) ?>
  <div style="clear: both;"></div>
  <br />
  </div>
<div>
  <img style="float: left; margin-right: 20px;" src="images/sem89_idhaem.png" alt="<?php echo $sem89Blocks[4] ?>" title="<?php echo $sem89Blocks[4] ?>" />
  <h3>5 - <?echo $sem89Blocks[4] ?></h3>
  <?php echo makeTestLink(getTestByIdentifier('7f6b9c72-4f3c-4147-b86b-9ca13c6d2e38')) ?>
  <div style="clear: both;"></div>
  <br />
</div>
<div>
  <img style="float: left; margin-right: 20px;" src="images/sem89_gastro.png" alt="<?php echo $sem89Blocks[5] ?>" title="<?php echo $sem89Blocks[5] ?>" />
  <h3>6 - <?echo $sem89Blocks[5] ?></h3>
  <?php echo makeTestLink(getTestByIdentifier('1e358058-9ea8-46fb-acb9-98d4664a3a24')) ?>
  <div style="clear: both;"></div>
</div>
</div>
<div style="clear: both;"></div>
</div>

<br />
<br />

<h1>PRE-CLINICALS</h1>

<div>
  <div style="float: left; width: 140px; margin-right: 20px; text-align: center;">
    <img src="images/crl.png" alt="<?php echo $preclinFullNames['CRL'] ?>" title="<?php $preclinFullNames['CRL'] ?>" />
  </div>
  <div style="float: left; width: 780px;">
    <h2><?php echo $preclinFullNames['CRL']?></h2>
    <br />
    <div>
      <div style="float: left; width: 220px; margin-right: 20px;">
        <h4>Quiz 1:</h4>
        <?php echo join(makeTestLinksByGroup('CRL', '/Quiz 1/'), '<br />') ?>
      </div>
      <div style="float: left; width: 220px; margin-right: 20px;">
        <h4>Quiz 2:</h4>
        <?php echo join(makeTestLinksByGroup('CRL', '/Quiz 2/'), '<br />') ?>
      </div>
      <div style="float: left; width: 220px; margin-right: 20px;">
        <h4>Quiz 3:</h4>
        <?php echo join(makeTestLinksByGroup('CRL', '/Quiz 3/'), '<br />') ?>
      </div>
      <div style="clear: both;"></div>
    </div>
  </div>
  <div style="clear: both"></div>
</div>

<br />
<br />

<div>
  <div style="float: left; width: 140px; margin-right: 20px; text-align: center;">
    <img src="images/csgd.png" alt="<?php echo $preclinFullNames['CSGD'] ?>" title="<?php $preclinFullNames['CSGD'] ?>" />
  </div>
  <div style="float: left; width: 780px;">
    <h2><?php echo $preclinFullNames['CSGD']?></h2>
    <br />
    <div>
      <div style="float: left; width: 220px; margin-right: 20px;">
        <h4>Quiz 1:</h4>
        <?php echo join(makeTestLinksByGroup('CSGD', '/Quiz 1/'), '<br />') ?>
      </div>
      <div style="float: left; width: 220px; margin-right: 20px;">
        <h4>Quiz 2:</h4>
        <?php echo join(makeTestLinksByGroup('CSGD', '/Quiz 2/'), '<br />') ?>
      </div>
      <div style="float: left; width: 220px; margin-right: 20px;">
        <h4>Quiz 3:</h4>
        <?php echo join(makeTestLinksByGroup('CSGD', '/Quiz 3/'), '<br />') ?>
      </div>
      <div style="clear: both;"></div>
    </div>
    <br />
    <div>
      <div style="float: left; width: 220px; margin-right: 20px;">
        <h4>Quiz 4:</h4>
        <?php echo join(makeTestLinksByGroup('CSGD', '/Quiz 4/'), '<br />') ?>
      </div>
      <div style="float: left; width: 220px; margin-right: 20px;">
        <h4>Other:</h5>
        <?php echo join(makeTestLinksByGroup('CSGD', '/SSSM/'), '<br />') ?>
      </div>
      <div style="clear: both;"></div>
    </div>
  </div>
  <div style="clear: both"></div>
</div>

<br />
<br />

<div>
  <div style="float: left; width: 140px; margin-right: 20px; text-align: center;">
    <img src="images/dmf.png" alt="<?php echo $preclinFullNames['DMF'] ?>" title="<?php $preclinFullNames['DMF'] ?>" />
  </div>
  <div style="float: left; width: 780px;">
    <h2><?php echo $preclinFullNames['DMF']?></h2>
    <br />
    <div>
      <div style="float: left; width: 220px; margin-right: 20px;">
        <h4>Week Quizzes:</h4>
        <?php echo join(makeTestLinksByGroup('DMF', '/Week/'), '<br />') ?>
      </div>
      <div style="float: left; width: 220px; margin-right: 20px;">
        <h4>Midsemester Tests:</h4>
        <?php echo join(makeTestLinksByGroup('DMF', '/MSE/'), '<br />') ?>
      </div>
      <div style="clear: both;"></div>
    </div>
  </div>
  <div style="clear: both"></div>
</div>

<br />
<br />

<div>
<div style="float: left; width: 140px; margin-right: 20px; text-align: center;">
  <img src="images/hp3.png" alt="<?php echo $preclinFullNames['HP3'] ?>" title="<?php $preclinFullNames['HP3'] ?>" />
</div>
<div style="float: left; width: 780px;">
  <h2><?php echo $preclinFullNames['HP3']?></h2>
  <br />
  <?php echo join(makeTestLinksByGroup('HP3', null), '<br />') ?>
</div>
<div style="clear: both"></div>
</div>

<br />
<br />

<div>
<div style="float: left; width: 140px; margin-right: 20px; text-align: center;">
  <img src="images/hp4.png" alt="<?php echo $preclinFullNames['HP4'] ?>" title="<?php $preclinFullNames['HP4'] ?>" />
</div>
<div style="float: left; width: 780px;">
  <h2><?php echo $preclinFullNames['HP4']?></h2>
  <br />
  <div>
    <div style="float: left; width: 220px; margin-right: 20px;">
      <h4>Lectures:</h4>
      <?php echo join(makeTestLinksByGroup('HP4', '/Lecture/'), '<br />') ?>
    </div>
    <div style="float: left; width: 220px; margin-right: 20px;">
      <h4>Quizzes:</h4>
      <?php echo join(makeTestLinksByGroup('HP4', '/Quiz/'), '<br />') ?>
    </div>
    <div style="clear: both;"></div>
  </div>
</div>
<div style="clear: both"></div>
</div>

<br />
<br />

<div>
<div style="float: left; width: 140px; margin-right: 20px; text-align: center;">
  <img src="images/hp5.png" alt="<?php echo $preclinFullNames['HP5'] ?>" title="<?php $preclinFullNames['HP5'] ?>" />
</div>
<div style="float: left; width: 780px;">
  <h2><?php echo $preclinFullNames['HP5']?></h2>
  <br />
  <?php echo join(makeTestLinksByGroup('HP5', null), '<br />') ?>
</div>
<div style="clear: both"></div>
</div>

<br />
<br />

<h1>FULL LISTING</h1>

<p>This sections lists all tests by category for reference.</p>

<ul>
<?php foreach($tgr->findAll() as $testGroup): ?>

  <li>
    <?php echo $testGroup->getDescription() ?> <!-- <i>(<?php echo $testGroup->getName() ?>)</i> -->
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
