<?php

namespace rubikscomplex\model;

use Doctrine\ORM\Mapping as ORM;

/**
 * rubikscomplex\model\TestGrouping
 */
class TestGrouping
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var integer $position
     */
    private $position;

    /**
     * @var rubikscomplex\model\Test
     */
    private $tests;

    /**
     * @var rubikscomplex\model\TestGroup
     */
    private $test_groups;


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
     * Set position
     *
     * @param integer $position
     * @return TestGrouping
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set tests
     *
     * @param rubikscomplex\model\Test $tests
     * @return TestGrouping
     */
    public function setTests(\rubikscomplex\model\Test $tests = null)
    {
        $this->tests = $tests;
        return $this;
    }

    /**
     * Get tests
     *
     * @return rubikscomplex\model\Test 
     */
    public function getTests()
    {
        return $this->tests;
    }

    /**
     * Set test_groups
     *
     * @param rubikscomplex\model\TestGroup $testGroups
     * @return TestGrouping
     */
    public function setTestGroups(\rubikscomplex\model\TestGroup $testGroups = null)
    {
        $this->test_groups = $testGroups;
        return $this;
    }

    /**
     * Get test_groups
     *
     * @return rubikscomplex\model\TestGroup 
     */
    public function getTestGroups()
    {
        return $this->test_groups;
    }
}