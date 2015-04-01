<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 */
class Event {

    /**
     * @ORM\Id 
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="ownEvents")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     **/
    private $owner;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublic;

    /**
     * @ORM\Column(type="string", length=120, unique=true, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $endDate;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $maxMembersNumber;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endRegistrationDate;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $address;
    
    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="joinedEvents")
     * @ORM\JoinTable(name="users_events")
     **/
    private $joinedUsers;

    public function isMembersLimit() {
        return $this->getMaxMembersNumber() !== null;
    }
    /**
     * 
     * @return false if no limit or integer
     */
    public function getNumberOfAvailablePlaces() {
        if($this->getMaxMembersNumber() === null) {
            return false;
        }
        else {
            return $this->getMaxMembersNumber() - $this->getJoinedUsers()->count();
        }
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
     * Set isPublic
     *
     * @param boolean $isPublic
     * @return Event
     */
    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    /**
     * Get isPublic
     *
     * @return boolean 
     */
    public function getIsPublic()
    {
        return $this->isPublic;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Event
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
     * @param string $description
     * @return Event
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Event
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return Event
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set maxMembersNumber
     *
     * @param integer $maxMembersNumber
     * @return Event
     */
    public function setMaxMembersNumber($maxMembersNumber)
    {
        $this->maxMembersNumber = $maxMembersNumber;

        return $this;
    }

    /**
     * Get maxMembersNumber
     *
     * @return integer 
     */
    public function getMaxMembersNumber()
    {
        return $this->maxMembersNumber;
    }

    /**
     * Set endRegistrationDate
     *
     * @param \DateTime $endRegistrationDate
     * @return Event
     */
    public function setEndRegistrationDate($endRegistrationDate)
    {
        $this->endRegistrationDate = $endRegistrationDate;

        return $this;
    }

    /**
     * Get endRegistrationDate
     *
     * @return \DateTime 
     */
    public function getEndRegistrationDate()
    {
        return $this->endRegistrationDate;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Event
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set owner
     *
     * @param \AppBundle\Entity\User $owner
     * @return Event
     */
    public function setOwner(\AppBundle\Entity\User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \AppBundle\Entity\User 
     */
    public function getOwner()
    {
        return $this->owner;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->joinedUsers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add joinedUsers
     *
     * @param \AppBundle\Entity\User $joinedUsers
     * @return Event
     */
    public function addJoinedUser(\AppBundle\Entity\User $joinedUsers)
    {
        $this->joinedUsers[] = $joinedUsers;

        return $this;
    }

    /**
     * Remove joinedUsers
     *
     * @param \AppBundle\Entity\User $joinedUsers
     */
    public function removeJoinedUser(\AppBundle\Entity\User $joinedUsers)
    {
        $this->joinedUsers->removeElement($joinedUsers);
    }

    /**
     * Get joinedUsers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getJoinedUsers()
    {
        return $this->joinedUsers;
    }
}
