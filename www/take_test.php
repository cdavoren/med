<?php
define('PAGE_SIZE', 10);
define('PAGE_OVERRUN', 5);

require_once('../lib/init.php');

$pageTitle = 'Test';

$em = App::getManager();

if (isset($_REQUEST['id'])) {
  $test = $em->find('\rubikscomplex\model\Test', intval($_REQUEST['id']));
  $pageTitle .= ' - '.$test->getTitle();

  if ($test !== null && isset($_REQUEST['correct_only'])) {
    $results = array();
    foreach ($test->getQuestions() as $question) {
      $q = array(
        'id' => $question->getId(),
        'number' => $question->getNumber(),
        'correct_answer' => $question->getCorrectAnswer(),
      );

      $results[$q['id']] = $q;
    }
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($results);
    return;
  }
}
else {
  $test = null;
}

$questions = $test->getQuestions();

if (isset($_REQUEST['page'])) {
  $pageNum = intval($_REQUEST['page']);
  $startIndex = ($pageNum - 1) * PAGE_SIZE;
  $endIndex = ($startIndex + PAGE_SIZE + PAGE_OVERRUN < count($questions)) ? $startIndex + PAGE_SIZE : $startIndex + PAGE_SIZE + PAGE_OVERRUN;
  $pageTitle .= ' - Page '.$pageNum;
}
else {
  $startIndex = 0;
  $endIndex = count($questions);
}

?>

<?php require_once('../template/header.php'); ?>

<h2><?php echo $test->getTitle() ?></h2>

<!--
<?php echo $startIndex ?><br />
<?php echo $endIndex ?>
-->

<form action="#" method="post" id="testform">
<p><i><?php echo count($questions) ?> question(s)</i></p>

<input type="hidden" id="test_id" name="test_id" value="<?php echo $test->getId() ?>" />

<?php foreach($questions as $i => $question): ?>

  <?php if ($i >= $startIndex && $i < $endIndex): ?>
    <input type="hidden" name="question_shown_<?php echo $question->getId() ?>" value="<?php echo $question->getNumber() ?>" />
    <h3>Q<?php echo $question->getNumber() ?></h3>
    <p><?php echo $question->getPrompt() ?></p>

    <?php foreach($question->getAnswers() as $answer): ?>

      <p style="margin: 0.3em 0px;"><input type="radio" name="question_answer_<?php echo $question->getId() ?>" value="<?php echo $answer->getQuestionIndex() ?>" />&nbsp;&nbsp;<?php echo $answer->getQuestionIndex() ?>.&nbsp;&nbsp;<?php echo $answer->getText() ?></p>
      
    <?php endforeach ?>
  <?php endif ?>
<?php endforeach ?>
<br />
<input type="submit" name="submit" id="test_submit" value="Mark" />
</form>
<br />
<div id="results" style="border: 1px #444 solid; padding: 10px;">
</div>
<hr />
<div id="paginator" style="text-align: center;">
<?php
if (isset($_REQUEST['page'])) {
  $pageNum = intval($_REQUEST['page']);
  $numPages = count($test->getQuestions());
  $currentPage = 1;
  while ($numPages > PAGE_OVERRUN) {
    if ($currentPage == $pageNum) {
      printf('%d&nbsp;', $currentPage);
    }
    else {
      printf('<a href="%s?id=%d&page=%d" title="%s - Page %d">%d</a>&nbsp;', 'take_test.php', $test->getId(), $currentPage, $test->getTitle(), $currentPage, $currentPage);
    }
    $currentPage++;
    $numPages -= PAGE_SIZE;
  }
}
?>
</div>

<script type="text/javascript">
$(document).ready(function() {
  $('#testform').ajaxForm({beforeSubmit: testSubmit});
});
</script>

<?php require_once('../template/footer.php'); ?>
