<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of venue_event
 *
 *
 * @author Matthew
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="venue_event")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\venue_eventRepository")
 */
class venue_event {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**		 
    *		
    * @ORM\ManyToOne(targetEntity="event", inversedBy="venue_event")		
    * @ORM\JoinColumn(name="event_id", referencedColumnName="id")		
    *		
    */		
    protected $event_id;
    
    /**
     * @ORM\ManyToOne(targetEntity="venue", inversedBy="venue_event")
     * @ORM\JoinColumn(name="venue_id", referencedColumnName="id")
     */
    protected $venue_id;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $doors;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $highCapacityAlert;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $highCapacityFlag;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $highHighCapacityAlert;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $highHighCapacityFlag;
    
    /**
    * @ORM\OneToMany(targetEntity="VenueCountAlerts", mappedBy="venueEvent", cascade={"persist"})
    * @ORM\OrderBy({"count" = "ASC"})
    * @Assert\Valid
    * @Assert\Collection(
    *     fields = {
    *         "description" = {
    *             @Assert\NotBlank(),
    *             @Assert\Length(
    *                 min = 3,
    *                 minMessage = "Description does not have enough characters"
    *             )
    *         },
    *         "upDownBoth" = {
    *             @Assert\NotBlank(),
    *         },
    *         "count" = {
    *             @Assert\NotBlank(),
    *         }
    *     },
    * )
    */
    protected $countAlerts;

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
     * Set inverse
     *
     * @param boolean $inverse
     *
     * @return venue_camera
     */
    public function setDoors($doors)
    {
        $this->doors = $doors;

        return $this;
    }

    /**
     * Get inverse
     *
     * @return boolean
     */
    public function getDoors()
    {
        return $this->doors;
    }

    /**
     * Set cameraId
     *
     * @param \AppBundle\Entity\camera $cameraId
     *
     * @return venue_camera
     */
    public function setEventId(\AppBundle\Entity\event $eventId = null)
    {
        $this->event_id = $eventId;

        return $this;
    }

    /**
     * Get cameraId
     *
     * @return \AppBundle\Entity\camera
     */
    public function getEventId()
    {
        return $this->event_id;
    }

    /**
     * Set venueId
     *
     * @param \AppBundle\Entity\venue $venueId
     *
     * @return venue_camera
     */
    public function setVenueId(\AppBundle\Entity\venue $venueId = null)
    {
        $this->venue_id = $venueId;

        return $this;
    }

    /**
     * Get venueId
     *
     * @return \AppBundle\Entity\venue
     */
    public function getVenueId()
    {
        return $this->venue_id;
    }
    
    /**
     * Get countAlerts
     */
    public function getCountAlerts()
    {
        return $this->countAlerts;
    }
    
    /**
     * Add countAlert
     *
     * @param \AppBundle\Entity\VenueCountAlerts $alert
     *
     * @return venue_event
     */
    public function addCountAlert(\AppBundle\Entity\VenueCountAlerts $alert)
    {
        //$this->locations[] = $location;
        $alert->setVenueEvent($this);
        //$this->locations->add($location);
        return $this;
    }
    /**
     * Remove countAlert
     *
     * @param \AppBundle\Entity\VenueCountAlerts $alert
     */
    public function removeCountAlert(\AppBundle\Entity\VenueCountAlerts $alert)
    {
        $this->countAlerts->removeElement($alert);
    }
}
