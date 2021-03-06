<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GeneralLog
 *
 * @author Nick
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Xiidea\EasyAuditBundle\Annotation\ORMSubscribedEvents;

/**
 * @ORM\Entity
 * @ORM\Table(name="general_log")
 * @ORMSubscribedEvents()
 */

class general_log {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\OneToOne(targetEntity="log_entries")
     * @ORM\JoinColumn(name="log_entry_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    private $log_entry_id;
    
    /**
     * @ORM\Column(type="text")
     */
    protected $general_description;
    
    /**
     * @ORM\Column(type="boolean")
     */
    protected $general_open;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $general_entry_closed_time;

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
     * Set generalDescription
     *
     * @param string $generalDescription
     *
     * @return general_log
     */
    public function setGeneralDescription($generalDescription)
    {
        $this->general_description = $generalDescription;

        return $this;
    }

    /**
     * Get generalDescription
     *
     * @return string
     */
    public function getGeneralDescription()
    {
        return $this->general_description;
    }

    /**
     * Set generalOpen
     *
     * @param boolean $generalOpen
     *
     * @return general_log
     */
    public function setGeneralOpen($generalOpen)
    {
        $this->general_open = $generalOpen;

        return $this;
    }

    /**
     * Get generalOpen
     *
     * @return boolean
     */
    public function getGeneralOpen()
    {
        return $this->general_open;
    }

    /**
     * Set generalEntryClosedTime
     *
     * @param \DateTime $generalEntryClosedTime
     *
     * @return general_log
     */
    public function setGeneralEntryClosedTime($generalEntryClosedTime)
    {
        $this->general_entry_closed_time = $generalEntryClosedTime;

        return $this;
    }

    /**
     * Get generalEntryClosedTime
     *
     * @return \DateTime
     */
    public function getGeneralEntryClosedTime()
    {
        return $this->general_entry_closed_time;
    }

    /**
     * Set logEntryId
     *
     * @param \AppBundle\Entity\log_entries $logEntryId
     *
     * @return general_log
     */
    public function setLogEntryId(\AppBundle\Entity\log_entries $logEntryId = null)
    {
        $this->log_entry_id = $logEntryId;

        return $this;
    }

    /**
     * Get logEntryId
     *
     * @return \AppBundle\Entity\log_entries
     */
    public function getLogEntryId()
    {
        return $this->log_entry_id;
    }
}
