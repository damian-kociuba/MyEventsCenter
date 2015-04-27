<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UserRepository")
 */
class User extends BaseUser {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    protected $facebook_id;

    /** @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true) */
    protected $facebook_access_token;

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

    /**
     * @ORM\OneToMany(targetEntity="Invitation", mappedBy="sender")
     * */
    private $sendInvitations;

    /**
     * @ORM\ManyToMany(targetEntity="Invitation", mappedBy="receivers")
     */
    private $receivedInvitations;

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
    private function addJoinedEvent(\AppBundle\Entity\Event $joinedEvents) {
        $this->joinedEvents[] = $joinedEvents;

        return $this;
    }

    /**
     * Remove joinedEvents
     *
     * @param \AppBundle\Entity\Event $joinedEvents
     */
    public function removeJoinedEvent(\AppBundle\Entity\Event $joinedEvents) {
        $this->joinedEvents->removeElement($joinedEvents);
    }

    /**
     * Get joinedEvents
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getJoinedEvents() {
        return $this->joinedEvents;
    }

    public function joinToEvent(\AppBundle\Entity\Event $event) {
        $this->addJoinedEvent($event);
        $event->addJoinedUser($this);
    }

    public function resignFromEvent(\AppBundle\Entity\Event $event) {
        $this->removeJoinedEvent($event);
        $event->removeJoinedUser($this);
    }

    /**
     * Add sendInvitations
     *
     * @param \AppBundle\Entity\Invitation $sendInvitations
     * @return User
     */
    public function addSendInvitation(\AppBundle\Entity\Invitation $sendInvitations) {
        $this->sendInvitations[] = $sendInvitations;

        return $this;
    }

    /**
     * Remove sendInvitations
     *
     * @param \AppBundle\Entity\Invitation $sendInvitations
     */
    public function removeSendInvitation(\AppBundle\Entity\Invitation $sendInvitations) {
        $this->sendInvitations->removeElement($sendInvitations);
    }

    /**
     * Get sendInvitations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSendInvitations() {
        return $this->sendInvitations;
    }

    /**
     * Add receivedInvitations
     *
     * @param \AppBundle\Entity\Invitation $receivedInvitations
     * @return User
     */
    public function addReceivedInvitation(\AppBundle\Entity\Invitation $receivedInvitations) {
        $this->receivedInvitations[] = $receivedInvitations;

        return $this;
    }

    /**
     * Remove receivedInvitations
     *
     * @param \AppBundle\Entity\Invitation $receivedInvitations
     */
    public function removeReceivedInvitation(\AppBundle\Entity\Invitation $receivedInvitations) {
        $this->receivedInvitations->removeElement($receivedInvitations);
    }

    /**
     * Get receivedInvitations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReceivedInvitations() {
        return $this->receivedInvitations;
    }

    /**
     * Set facebook_id
     *
     * @param integer $facebookId
     * @return User
     */
    public function setFacebookId($facebookId) {
        $this->facebook_id = $facebookId;

        return $this;
    }

    /**
     * Get facebook_id
     *
     * @return integer 
     */
    public function getFacebookId() {
        return $this->facebook_id;
    }

    /**
     * Set facebook_access_token
     *
     * @param string $facebookAccessToken
     * @return User
     */
    public function setFacebookAccessToken($facebookAccessToken) {
        $this->facebook_access_token = $facebookAccessToken;

        return $this;
    }

    /**
     * Get facebook_access_token
     *
     * @return string 
     */
    public function getFacebookAccessToken() {
        return $this->facebook_access_token;
    }

}
