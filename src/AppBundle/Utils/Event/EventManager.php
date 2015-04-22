<?php

namespace AppBundle\Utils\Event;

use AppBundle\Entity\Event;
use AppBundle\Entity\User;

/**
 * @author dkociuba
 */
class EventManager {

    /**
     * @param User|null $user
     * @param Event $event
     * @return boolean
     */
    public function isUserMemberOfEvent($user, Event $event) {
        if ($user === null) {
            return false;
        }
        foreach ($event->getJoinedUsers() as $member) {
            if ($member->getId() === $user->getId()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param User|null $user
     * @param Event $event
     * @return boolean
     */
    public function isUserOwnerOfEvent($user, Event $event) {
        if ($user === null) {
            return false;
        }
        return $user->getId() === $event->getOwner()->getId();
    }

    /**
     * @param User|null $user
     * @param Event $event
     * @return boolean
     */
    public function isUserAllowedToJoinEvent($user, Event $event) {
        if($event->getIsPublic()) {
            return true;
        }
        else {
            return $this->isUserInvitedToEvent($user, $event);
        }
    }
    /**
     * @param User|null $user
     * @param Event $event
     * @return boolean
     */
    public function isUserInvitedToEvent($user, Event $event) {
        $invitation = $event->getInvitation();
        if ($invitation === null || $user === null) {
            return false;
        }

        foreach ($invitation->getReceivers() as $receiver) {
            if ($receiver->getId() === $user->getId()) {
                return true;
            }
        }

        return false;
    }

}
