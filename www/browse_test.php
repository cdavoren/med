<?php

require_once('../lib/init.php');

$pageTitle = 'Browse Test';

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
}
else {
  $test = null;
}

$breadcrumbTrail = array(
  array(
    'name' => 'Home',
    'url' => $config['app_root']),
  array(
    'name' => 'Browse '.$test->getTitle(),
    'url' => $_SERVER['SCRIPT_NAME'].sprintf('?id=', $test->getId())));

$questions = $test->getQuestions();

?>

<?php require_once('../template/header.php') ?>

<h1><?php echo $test->getTitle() ?> (Browsing)</h1>

<?php foreach ($questions as $i => $question): ?>
  <div>
  <div style="float: left; width: 60px; margin-right: 20px;">
    <span style="font-size: 36px; font-weight: bold">Q<?php echo $question->getNumber() ?></span>
  </div>
  <div style="float: left; width: 760px; margin-right: 20px; padding-top: 10px;">
    <p><?php echo str_replace("\n\n", '<br />', $question->getPrompt()) ?></p>
    <?php foreach($question->getAnswers() as $answer): ?>
      <?php if ($answer->getQuestionIndex() == $question->getCorrectAnswer()): ?>
        <span style="font-weight: bold; color: white;">
      <?php else: ?>
        <span>
      <?php endif ?>
      <?php echo sprintf('%s. %s', getAnswerChar($question, $answer->getQuestionIndex()), $answer->getText()) ?><br />
      </span>
    <?php endforeach ?>
    <?php if ($question->getExplanation() !== null && strlen(trim($question->getExplanation())) > 0): ?>
      <br />
      <div style="border: 1px solid #444; border-radius: 6px; padding: 10px;">
        <h4>Explanation:</h4>
        <br />
        <?php echo str_replace("\n", '<br />', $question->getExplanation()) ?>
      </div>
    <?php endif ?>
  </div>
  <div style="clear: both;"></div>
  </div>
  <br />
<?php endforeach ?>

<?php require_once('../template/footer.php') ?>
