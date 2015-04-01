<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class User extends BaseUser {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Event", mappedBy="owner")
     */
    private $ownEvents;

    /**
     * @ORM\ManyToMany(targetEntity="Event", mappedBy="joinedUsers")
     */
    private $joinedEvents;

    /**
     * @ORM\Column(type="integer", length=1, options={"comment":"0-woman, 1-man"})
     */
    protected $gender;

    /**
     *
     * @ORM\column(type="date")
     */
    protected $birthDate;

    public function __construct() {
        parent::__construct();
        // your own logic
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set gender
     *
     * @param integer $gender
     * @return User
     */
    public function setGender($gender) {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return integer 
     */
    public function getGender() {
        return $this->gender;
    }

    /**
     * Set birthDate
     *
     * @param \DateTime $birthDate
     * @return User
     */
    public function setBirthDate($birthDate) {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \DateTime 
     */
    public function getBirthDate() {
        return $this->birthDate;
    }

    /**
     * Add ownEvents
     *
     * @param \AppBundle\Entity\Event $ownEvents
     * @return User
     */
    public function addOwnEvent(\AppBundle\Entity\Event $ownEvents) {
        $this->ownEvents[] = $ownEvents;

        return $this;
    }

    /**
     * Remove ownEvents
     *
     * @param \AppBundle\Entity\Event $ownEvents
     */
    public function removeOwnEvent(\AppBundle\Entity\Event $ownEvents) {
        $this->ownEvents->removeElement($ownEvents);
    }

    /**
     * Get ownEvents
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOwnEvents() {
        return $this->ownEvents;
    }


    /**
     * Add joinedEvents
     *
     * @param \AppBundle\Entity\Event $joinedEvents
     * @return User
     */
    private function addJoinedEvent(\AppBundle\Entity\Event $joinedEvents)
    {
        $this->joinedEvents[] = $joinedEvents;

        return $this;
    }

    /**
     * Remove joinedEvents
     *
     * @param \AppBundle\Entity\Event $joinedEvents
     */
    public function removeJoinedEvent(\AppBundle\Entity\Event $joinedEvents)
    {
        $this->joinedEvents->removeElement($joinedEvents);
    }

    /**
     * Get joinedEvents
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getJoinedEvents()
    {
        return $this->joinedEvents;
    }
    
    public function joinToEvent(\AppBundle\Entity\Event $event) {
        $this->addJoinedEvent($event);
        $event->addJoinedUser($this);
    }
}
