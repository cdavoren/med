<?php

namespace rubikscomplex\model;

use Doctrine\ORM\Mapping as ORM;

/**
 * rubikscomplex\model\Answer
 */
class Answer
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var text $text
     */
    private $text;

    /**
     * @var integer $question_index
     */
    private $question_index;

    /**
     * @var rubikscomplex\model\Question
     */
    private $question;


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
     * Set text
     *
     * @param text $text
     * @return Answer
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * Get text
     *
     * @return text 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set question_index
     *
     * @param integer $questionIndex
     * @return Answer
     */
    public function setQuestionIndex($questionIndex)
    {
        $this->question_index = $questionIndex;
        return $this;
    }

    /**
     * Get question_index
     *
     * @return integer 
     */
    public function getQuestionIndex()
    {
        return $this->question_index;
    }

    /**
     * Set question
     *
     * @param rubikscomplex\model\Question $question
     * @return Answer
     */
    public function setQuestion(\rubikscomplex\model\Question $question = null)
    {
        $this->question = $question;
        return $this;
    }

    /**
     * Get question
     *
     * @return rubikscomplex\model\Question 
     */
    public function getQuestion()
    {
        return $this->question;
    }
}