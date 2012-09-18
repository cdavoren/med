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
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $test_groups;

    public function __construct()
    {
        $this->test_groups = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add test_groups
     *
     * @param rubikscomplex\model\TestGroups $testGroups
     * @return TestGrouping
     */
    public function addTestGroups(\rubikscomplex\model\TestGroups $testGroups)
    {
        $this->test_groups[] = $testGroups;
        return $this;
    }

    /**
     * Get test_groups
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getTestGroups()
    {
        return $this->test_groups;
    }

    /**
     * Add test_groups
     *
     * @param rubikscomplex\model\TestGroup $testGroups
     * @return TestGrouping
     */
    public function addTestGroup(\rubikscomplex\model\TestGroup $testGroups)
    {
        $this->test_groups[] = $testGroups;
        return $this;
    }
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $tests;


    /**
     * Add tests
     *
     * @param rubikscomplex\model\Test $tests
     * @return TestGrouping
     */
    public function addTest(\rubikscomplex\model\Test $tests)
    {
        $this->tests[] = $tests;
        return $this;
    }

    /**
     * Get tests
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getTests()
    {
        return $this->tests;
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
}