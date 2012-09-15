<?php

namespace rubikscomplex\model;

use Doctrine\ORM\Mapping as ORM;

/**
 * rubikscomplex\model\Question
 */
class Question
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $identifier
     */
    private $identifier;

    /**
     * @var text $prompt
     */
    private $prompt;

    /**
     * @var integer $correct_answer
     */
    private $correct_answer;

    /**
     * @var integer $answer_type
     */
    private $answer_type;

    /**
     * @var text $explanation
     */
    private $explanation;

    /**
     * @var integer $number
     */
    private $number;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $answers;

    /**
     * @var rubikscomplex\model\Test
     */
    private $test;

    public function __construct()
    {
        $this->answers = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set identifier
     *
     * @param string $identifier
     * @return Question
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
        return $this;
    }

    /**
     * Get identifier
     *
     * @return string 
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Set prompt
     *
     * @param text $prompt
     * @return Question
     */
    public function setPrompt($prompt)
    {
        $this->prompt = $prompt;
        return $this;
    }

    /**
     * Get prompt
     *
     * @return text 
     */
    public function getPrompt()
    {
        return $this->prompt;
    }

    /**
     * Set correct_answer
     *
     * @param integer $correctAnswer
     * @return Question
     */
    public function setCorrectAnswer($correctAnswer)
    {
        $this->correct_answer = $correctAnswer;
        return $this;
    }

    /**
     * Get correct_answer
     *
     * @return integer 
     */
    public function getCorrectAnswer()
    {
        return $this->correct_answer;
    }

    /**
     * Set answer_type
     *
     * @param integer $answerType
     * @return Question
     */
    public function setAnswerType($answerType)
    {
        $this->answer_type = $answerType;
        return $this;
    }

    /**
     * Get answer_type
     *
     * @return integer 
     */
    public function getAnswerType()
    {
        return $this->answer_type;
    }

    /**
     * Set explanation
     *
     * @param text $explanation
     * @return Question
     */
    public function setExplanation($explanation)
    {
        $this->explanation = $explanation;
        return $this;
    }

    /**
     * Get explanation
     *
     * @return text 
     */
    public function getExplanation()
    {
        return $this->explanation;
    }

    /**
     * Set number
     *
     * @param integer $number
     * @return Question
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    /**
     * Get number
     *
     * @return integer 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Add answers
     *
     * @param rubikscomplex\model\Answer $answers
     * @return Question
     */
    public function addAnswer(\rubikscomplex\model\Answer $answers)
    {
        $this->answers[] = $answers;
        return $this;
    }

    /**
     * Get answers
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * Set test
     *
     * @param rubikscomplex\model\Test $test
     * @return Question
     */
    public function setTest(\rubikscomplex\model\Test $test = null)
    {
        $this->test = $test;
        return $this;
    }

    /**
     * Get test
     *
     * @return rubikscomplex\model\Test 
     */
    public function getTest()
    {
        return $this->test;
    }
}