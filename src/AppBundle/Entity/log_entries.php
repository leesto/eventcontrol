<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of log_entries
 *
 * @author Nick
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="log_entries")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\log_entriesRepository")
 */

class log_entries {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $log_timestamp;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $log_update_timestamp;
    
    /**
     * @ORM\Column(type="string", length=150)
     */
    protected $log_blurb;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="log_entries")
     * @ORM\JoinColumn(name="User_id", referencedColumnName="id")
     */
    private $operator;
    
    /**
     * @ORM\ManyToOne(targetEntity="event", inversedBy="log_entries")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $event;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $log_entry_open_time;
    
    /**
     * @ORM\Column(type="text")
     */
    protected $location;
    
    /**
     * @ORM\Column(type="text")
     */
    protected $reported_by;

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
     * Set logTimestamp
     *
     * @param \DateTime $logTimestamp
     *
     * @return log_entries
     */
    public function setLogTimestamp($logTimestamp)
    {
        $this->log_timestamp = $logTimestamp;

        return $this;
    }

    /**
     * Get logTimestamp
     *
     * @return \DateTime
     */
    public function getLogTimestamp()
    {
        return $this->log_timestamp;
    }

    /**
     * Set logUpdateTimestamp
     *
     * @param \DateTime $logUpdateTimestamp
     *
     * @return log_entries
     */
    public function setLogUpdateTimestamp($logUpdateTimestamp)
    {
        $this->log_update_timestamp = $logUpdateTimestamp;

        return $this;
    }

    /**
     * Get logUpdateTimestamp
     *
     * @return \DateTime
     */
    public function getLogUpdateTimestamp()
    {
        return $this->log_update_timestamp;
    }
    
    
    /**
     * Set logBlurb
     *
     * @param string $logBlurb
     *
     * @return log_entries
     */
    public function setLogBlurb($logBlurb)
    {
        $this->log_blurb = $logBlurb;

        return $this;
    }

    /**
     * Get logBlurb
     *
     * @return string
     */
    public function getLogBlurb()
    {
        return $this->log_blurb;
    }

    /**
     * Set logEntryOpenTime
     *
     * @param \DateTime $logEntryOpenTime
     *
     * @return log_entries
     */
    public function setLogEntryOpenTime($logEntryOpenTime)
    {
        $this->log_entry_open_time = $logEntryOpenTime;

        return $this;
    }

    /**
     * Get logEntryOpenTime
     *
     * @return \DateTime
     */
    public function getLogEntryOpenTime()
    {
        return $this->log_entry_open_time;
    }

    /**
     * Set location
     *
     * @param string $location
     *
     * @return log_entries
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set reportedBy
     *
     * @param string $reportedBy
     *
     * @return log_entries
     */
    public function setReportedBy($reportedBy)
    {
        $this->reported_by = $reportedBy;

        return $this;
    }

    /**
     * Get reportedBy
     *
     * @return string
     */
    public function getReportedBy()
    {
        return $this->reported_by;
    }

    /**
     * Set operator
     *
     * @param \AppBundle\Entity\User $operator
     *
     * @return log_entries
     */
    public function setOperator(\AppBundle\Entity\User $operator = null)
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * Get operator
     *
     * @return \AppBundle\Entity\User
     */
    public function getOperator()
    {
        return $this->operator;
    }
    
    /**
     * Set event
     *
     * @param \AppBundle\Entity\event $event
     *
     * @return log_entries
     */
    public function setEvent(\AppBundle\Entity\event $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return \AppBundle\Entity\event
     */
    public function getEvent()
    {
        return $this->event;
    }
}
