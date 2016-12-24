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
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
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
    private $User_id;
    
    /**
     * @ORM\ManyToOne(targetEntity="event", inversedBy="log_entries")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    private $event_id;
    
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
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $park_alert;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $latitude;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $longitude;

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
        $this->User_id = $operator;

        return $this;
    }

    /**
     * Get operator
     *
     * @return \AppBundle\Entity\User
     */
    public function getOperator()
    {
        return $this->User_id;
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
        $this->event_id = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return \AppBundle\Entity\event
     */
    public function getEvent()
    {
        return $this->event_id;
    }
    
    /** Set park_alert
     *
     * @param boolean $park_alert
     *
     * @return log_entries
     */
    public function setParkAlert($park_alert)
    {
        $this->park_alert = $park_alert;

        return $this;
    }

    /**
     * Get park_alert
     *
     * @return boolean
     */
    public function getParkAlert()
    {
        return $this->park_alert;
    }
    
    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return log_entries
     */
    public function setLatitude($latitude)
    {
        if($this->id)
        {
            //Do nothing
        } else {
            $this->latitude = $latitude;
        }
        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }
    
    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return log_entries
     */
    public function setLongitude($longitude)
    {
        if($this->id)
        {
            //Do nothing
        } else {
            $this->longitude = $longitude;
        }
        
        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }
}
