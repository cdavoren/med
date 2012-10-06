<?php
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

?>

<?php require_once('../template/header.php'); ?>

<h2><?php echo $test->getTitle() ?></h2>

<form action="#" method="post" id="testform">
<p><i><?php echo count($test->getQuestions()) ?> question(s)</i></p>

<input type="hidden" id="test_id" name="test_id" value="<?php echo $test->getId() ?>" />

<?php foreach($test->getQuestions() as $question): ?>

  <input type="hidden" name="question_shown_<?php echo $question->getId() ?>" value="<?php echo $question->getNumber() ?>" />
  <h3>Q<?php echo $question->getNumber() ?></h3>
  <p><?php echo $question->getPrompt() ?></p>

  <?php foreach($question->getAnswers() as $answer): ?>

    <p style="margin: 0.3em 0px;"><input type="radio" name="question_answer_<?php echo $question->getId() ?>" value="<?php echo $answer->getQuestionIndex() ?>" />&nbsp;&nbsp;<?php echo $answer->getQuestionIndex() ?>.&nbsp;&nbsp;<?php echo $answer->getText() ?></p>
    
  <?php endforeach ?>

<?php endforeach ?>
<input type="submit" name="submit" id="test_submit" value="Mark" />
</form>
<div id="results">
</div>

<script type="text/javascript">
$(document).ready(function() {
  $('#testform').ajaxForm({beforeSubmit: testSubmit});
});
</script>

<?php require_once('../template/footer.php'); ?>
