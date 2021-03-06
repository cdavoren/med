<?php

namespace rubikscomplex\util;

use \Symfony\Component\Yaml\Parser;
use rubikscomplex\model\Test;
use rubikscomplex\model\Question;
use rubikscomplex\model\Answer;
use rubikscomplex\model\TestGroup;
use rubikscomplex\model\TestGrouping;

class Importer {
    public static function importYML($filename, \Doctrine\ORM\EntityManager $em, &$debug=null, $subjectGroupDescriptions = array()) {
        $error = null;
        $test = null;
        $subjectGroup = null;
        $subjectGrouping = null;
        $questions = array();
        $answers = array();

        $subjectGroups = array();
        $subjectGroupings = array();

        if ($debug === null) {
            $debug = array();
        }
        if (!isset($debug['output'])) {
            $debug['output'] = '';
        }

        $parser = new Parser();

        $value = $parser->parse(file_get_contents($filename));

        $test = $em->getRepository('rubikscomplex\model\Test')->findOneBy(array('identifier'=>$value['test']['id']));
        if ($test === null) {
            $test = new Test();
            $debug['output'] .= sprintf('<p>Have to create test (%s).</p>', $value['test']['id']);
        }
        else {
            $debug['output'] .= sprintf('<p>Test exists in database (%s).</p>', $test->getIdentifier());
        }

        $test->setIdentifier($value['test']['id']);
        $test->setTitle($value['test']['title']);
        $test->setDescription($value['test']['description'] == null ? '' : $value['test']['description']);
        $test->setYear($value['test']['year'] == null ? 0 : $value['test']['year']);

        if ($value['test']['subjects'] !== null) {
            foreach($value['test']['subjects'] as $subjectGroupName) {
                $subjectGrouping = null;
                $debug['output'] .= sprintf('<p>For test "%s" found group id "%s"</p>', $value['test']['title'], $subjectGroupName);
                $subjectGroup = $em->getRepository('rubikscomplex\model\TestGroup')->findOneBy(array('name'=>$subjectGroupName));

                if ($subjectGroup === null) {
                    $debug['output'] .= sprintf('<p>Had to create new test group for subject (%s).</p>', $subjectGroupName);
                    $subjectGroup = new TestGroup();

                    $subjectGroup->setName($subjectGroupName);
                    if (array_key_exists($subjectGroup->getName(), $subjectGroupDescriptions)) {
                      $subjectGroup->setDescription($subjectGroupDescriptions[$subjectGroup->getName()]);
                    }
                    else {
                      $subjectGroup->setDescription('Subject.');
                    }
                }
                else {
                    $debug['output'] .= sprintf('<p>Test group for subject already exists (%s, %d).</p>', $subjectGroup->getName(), $subjectGroup->getId());
                    if ($em->contains($test)) {
                        $subjectGrouping = $em->getRepository('rubikscomplex\model\TestGrouping')->findOneBy(array('test_groups'=>$subjectGroup, 'tests'=>$test));
                    }
                }

                $subjectGroups[] = $subjectGroup;

                if ($subjectGrouping === null) {
                    $debug['output'] .= sprintf('<p>Had to create new test grouping for subject (%s).</p>', $subjectGroupName);
                    $maxpos = 0;
                    $prevassocs = $em->getRepository('rubikscomplex\model\TestGrouping')->findBy(array('test_groups' => $subjectGroup->getId()));
                    foreach ($prevassocs as $pa) {
                        if ($pa->getPosition() > $maxpos) {
                            $maxpos = $pa->getPosition();
                        }
                    }

                    $subjectGrouping = new TestGrouping();
                    $subjectGrouping->setTests($test);
                    $subjectGrouping->setTestGroups($subjectGroup);
                    $subjectGrouping->setPosition($maxpos+1);
                    $subjectGroupings[] = $subjectGrouping;
                }
                else {
                    $debug['output'] .= sprintf('<p>Test grouping for subject and test already exists (%s, %d).</p>', $subjectGroupName, $subjectGrouping->getId());
                }
            }
        }

        foreach($value['test']['questions'] as $q) {
            $question = $em->getRepository('rubikscomplex\model\Question')->findOneBy(array('identifier'=>$q['id']));
            if ($question === null) {
                $question = new Question();
                $debug['output'] .= sprintf('<p>Have to create new question (%d, %s).</p>', $q['number'], $q['id']);
            }
            else {
                $debug['output'] .= sprintf('<p>Question exists in database (%d, %s).</p>', $question->getNumber(), $question->getIdentifier());
            }
            $questions[] = $question;
            $question->setTest($test);
            
            $question->setNumber($q['number']);
            $question->setIdentifier($q['id']);
            $question->setAnswerType($q['answer_type']);
            $question->setPrompt($q['prompt']);
            if ($question->getPrompt() == null) {
                $debug['output'] .= sprintf('<p>Warning: question %d has null prompt.</p>', $question->getNumber());
            }
            $question->setExplanation($q['explanation'] == null ? '' : $q['explanation']);
            $question->setCorrectAnswer($q['correct_answer']);

            $debug['output'] .= sprintf('<p>');
            foreach($q['answers'] as $a) {
                // We aren't so concerned about the uniqueness of answers.
                $answer = $em->getRepository('rubikscomplex\model\Answer')->findOneBy(array('question'=>$question->getId(), 'question_index'=>$a['index']));
                if ($answer === null) {
                    $answer = new Answer();
                    $answer->setQuestion($question);
                    $debug['output'] .= sprintf('Had to create answer %d.<br />', $a['index']);
                }
                else {
                    $debug['output'] .= sprintf('Updating answer %d (id: %d).<br />', $answer->getQuestionIndex(), $answer->getId());
                }
                $answers[] = $answer;

                $answer->setText($a['text']);
                $answer->setQuestionIndex($a['index']);
            }
            $debug['output'] .= sprintf('</p>');
        }

        if ($error === null) {
            // Commit new test to database
            $em->persist($test);
            $em->flush();
            foreach ($questions as $question) {
                $em->persist($question);
            }
            foreach ($answers as $answer) {
                $em->persist($answer);
            }
            foreach ($subjectGroups as $subjectGroup) {
                $em->persist($subjectGroup);
                $em->flush();
            }
            foreach ($subjectGroupings as $subjectGrouping) {
                $em->persist($subjectGrouping);
            }
            $em->flush();
        }

        return $error;
    }
}
