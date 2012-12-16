<?php
define('PAGE_SIZE', 10);
define('PAGE_OVERRUN', 5);

require_once('../lib/init.php');

$pageTitle = 'Test';

$em = App::getManager();
$config = App::getConfiguration();

function getAnswerChar($question, $answerIndex) {
  if ($question->getAnswerType() == 1) {
    return chr($answerIndex+64);
  }
  else {
    return chr($answerIndex+48);
  }
}

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
        'answer_type' => $question->getAnswerType(),
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

$breadcrumbTrail = array(
  array(
    'name' => 'Home', 
    'url' => $config['app_root']));

if (isset($_REQUEST['page'])) {
  $breadcrumbTrail[] = array(
    'name' => $test->getTitle().sprintf(' (page %s)', $_REQUEST['page']), 
    'url' => $_SERVER['SCRIPT_NAME'].sprintf('?id=%d&page=%s', $test->getId(), $_REQUEST['page']));
  $pageNum = intval($_REQUEST['page']);
  $startIndex = ($pageNum - 1) * PAGE_SIZE;
  $endIndex = ($startIndex + PAGE_SIZE + PAGE_OVERRUN < count($questions)) ? $startIndex + PAGE_SIZE : $startIndex + PAGE_SIZE + PAGE_OVERRUN;
  $pageTitle .= ' - Page '.$pageNum;
}
else {
  $breadcrumbTrail[] = array(
    'name' => $test->getTitle(), 
    'url' => $_SERVER['SCRIPT_NAME'].sprintf('?id=%d', $test->getId()));
  $startIndex = 0;
  $endIndex = count($questions);
}

?>

<?php require_once('../template/header.php'); ?>

<h1><?php echo $test->getTitle() ?></h1>

<!--
<?php echo $startIndex ?><br />
<?php echo $endIndex ?>
-->

<form action="#" method="post" id="testform">
<p>
<i>
  <?php echo count($questions) ?> question(s)
  <?php if (isset($_REQUEST['page'])): ?>
    <?php echo sprintf(' - page %s', $_REQUEST['page']) ?>
  <?php endif ?>
</i>
</p>

<input type="hidden" id="test_id" name="test_id" value="<?php echo $test->getId() ?>" />

<?php foreach($questions->slice($startIndex, $endIndex-$startIndex) as $i => $question): ?>

    <input type="hidden" name="question_shown_<?php echo $question->getId() ?>" value="<?php echo $question->getNumber() ?>" />
    <div id="question_<?php echo $question->getNumber(); ?>">
    <div style="float: left; width: 60px; margin-right: 20px;">
      <span style="font-size: 36px; font-weight: bold">Q<?php echo $question->getNumber() ?></span>
    </div>
    <div style="float: left; width: 620px; margin-right: 20px; padding-top: 10px;">
      <p><?php echo str_replace("\n\n",'<br />', $question->getPrompt()) ?></p>

      <?php foreach($question->getAnswers() as $answer): ?>

        <?php $radioId = 'radio_qa_'.$question->getId().'_'.$answer->getId() ?> 

        <label for="<?php echo $radioId ?>"><p style="margin: 0em 0px; padding: 0.3em 1px 0.5em 1px" onmouseover="javascript:$(this).css('background-color', '#3D3D3D');" onmouseout="javascript:$(this).css('background-color', '');"><input type="radio" name="question_answer_<?php echo $question->getId() ?>" id="<?php echo $radioId ?>" value="<?php echo $answer->getQuestionIndex() ?>" />&nbsp;&nbsp;<?php echo getAnswerChar($question, $answer->getQuestionIndex()) ?>.&nbsp;&nbsp;<?php echo $answer->getText() ?></p></label>
      <?php endforeach ?>
      <div class="explanation" style="display: none;">
        <?php if ($question->getExplanation() !== null && strlen(trim($question->getExplanation())) > 0): ?>
          <br />
          <div style="border: 1px solid #444; border-radius: 6px; padding: 10px; ">
            <h4>Explanation:</h4>
            <br />
            <?php echo str_replace("\n", '<br />', $question->getExplanation()) ?>
          </div>
        <?php endif ?>
      </div>
    </div>
    <div style="float: left; width: 220px;">
      <div class="correct" style="display: none;">
        <img src="images/circle_tick.png" alt="Correct" title="Correct" /><br />
        <span class="correct_label">CORRECT</span>
        <p>You correctly answered <b><?php echo getAnswerChar($question, $question->getCorrectAnswer()) ?></b>.</p>
      </div>
      <div class="incorrect" style="display: none;">
        <img src="images/triangle_cross.png" alt="Incorrect" title="Incorrect" /><br />
        <span class="incorrect_label">INCORRECT</span>
        <p>The correct answer was <b><?php echo getAnswerChar($question, $question->getCorrectAnswer()) ?></b>.</p>
      </div>
    </div>
    <div style="clear: both;"></div>
    </div> 
    <br />
<?php endforeach ?>
<br />
<input type="submit" name="submit" id="test_submit" value="Mark" />
</form>
<br />
<div id="results" style="border: 1px #444 solid; padding: 10px; display: none;">
  <h4>Result:</h4> 
  <span id="result_correct_total"></span>
  <br />
  <br />
  <h4>Breakdown:</h4>
  <?php foreach ($questions->slice($startIndex, $endIndex-$startIndex) as $i => $question): ?>
    <a href="#question_<?php echo $question->getNumber() ?>">Q<?php echo $question->getNumber() ?></a>: <span id="question_<?php echo $question->getId() ?>_feedback"></span></br />
  <?php endforeach ?>
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
