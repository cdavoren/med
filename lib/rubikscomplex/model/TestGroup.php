<?php

namespace rubikscomplex\model;

use Doctrine\ORM\Mapping as ORM;

/**
 * rubikscomplex\model\TestGroup
 */
class TestGroup
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var text $description
     */
    private $description;


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
     * Set name
     *
     * @param string $name
     * @return TestGroup
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param text $description
     * @return TestGroup
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * @var rubikscomplex\model\TestGrouping
     */
    private $test_groupings;


    /**
     * Set test_groupings
     *
     * @param rubikscomplex\model\TestGrouping $testGroupings
     * @return TestGroup
     */
    public function setTestGroupings(\rubikscomplex\model\TestGrouping $testGroupings = null)
    {
        $this->test_groupings = $testGroupings;
        return $this;
    }

    /**
     * Get test_groupings
     *
     * @return rubikscomplex\model\TestGrouping 
     */
    public function getTestGroupings()
    {
        return $this->test_groupings;
    }
    public function __construct()
    {
        $this->test_groupings = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add test_groupings
     *
     * @param rubikscomplex\model\TestGrouping $testGroupings
     * @return TestGroup
     */
    public function addTestGrouping(\rubikscomplex\model\TestGrouping $testGroupings)
    {
        $this->test_groupings[] = $testGroupings;
        return $this;
    }
}