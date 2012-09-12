<?php header('Content-Type: application/json; charset=utf-8') ?>
{"success":true}
<?php

require_once('../../lib/bootstrap.php');

if (isset($_SESSION['userid'])) {
  unset($_SESSION['userid']);
}

?>
