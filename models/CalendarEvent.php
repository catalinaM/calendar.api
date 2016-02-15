<?php
namespace Models;

use Mappers\CalendarMapper;
use Mappers\EventMapper;

class CalendarEvent
{
    /**@var Event $event */
    private $event;

    /**@var Calendar $user */
    private $calendar;

    /**@var User $user */
    private $user;

    public function __construct(User $user, Calendar $calendar, Event $event)
    {
        $this->event = $event;
        $this->user = $user;
        $this->calendar = $calendar;
    }

    /**
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param Event $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * @return Calendar
     */
    public function getCalendar()
    {
        return $this->calendar;
    }

    /**
     * @param Calendar $calendar
     */
    public function setCalendar($calendar)
    {
        $this->calendar = $calendar;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    static function createEventForUserCalendar(User $user, $eventId, $calendarId) {
        $event = EventMapper::getInstance()->getById($eventId);
        $calendar = CalendarMapper::getInstance()->getById($calendarId);

        return new self($user, $calendar, $event);
    }
}