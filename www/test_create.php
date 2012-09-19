<?php

require_once('../lib/bootstrap.php');

use rubikscomplex\util\UUID;

$pageTitle = 'Create Test';
require('../template/header.php');

?>

<h1>CREATE TEST</h1>

<?php
// For debug purposes, clear all tests, questions and answers
foreach($em->getRepository('\rubikscomplex\model\Answer')->findAll() as $i) {
  $em->remove($i);
}
foreach($em->getRepository('\rubikscomplex\model\Question')->findAll() as $i) {
  $em->remove($i);
}
foreach($em->getRepository('\rubikscomplex\model\Test')->findAll() as $i) {
  $em->remove($i);
}
$em->flush();


// Now create some new data...
$test = new \rubikscomplex\model\Test();
$test->setIdentifier(UUID.v4());
$em->persist($test);

$test->setTitle('Prototype Test');
$test->setDescription('Purely experimental test.');
$test->setYear(2012);

for($i = 0; $i < 5; $i++) {
  $question = new \rubikscomplex\model\Question();
  $em->persist($question);

  $question->setNumber($i+1);
  $question->setPrompt('Question text for question '.($i+1).'.');
  $question->setExplanation('None.');
  $question->setAnswerType(0);
  $question->setIdentifier(UUID.v4());
  $question->setCorrectAnswer(rand(0, 4));

  for($j = 0; $j < 5; $j++) {
    $answer = new \rubikscomplex\model\Answer();
    $em->persist($answer);
    
    $answer->setText('This is answer number '.($j+1));
    $answer->setQuestionIndex($j);
    $answer->setQuestion($question);
    $question->addAnswer($answer);
  }

  $question->setTest($test);
  $test->addQuestion($question);
}

echo '<pre>';
$em->flush();
echo '</pre>';

?>
<p>Done.</p>
<?php

require('../template/footer.php');

?>
