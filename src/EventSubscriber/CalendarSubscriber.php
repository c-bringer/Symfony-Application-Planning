<?php

namespace App\EventSubscriber;

use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CalendarSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents() {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData'
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar) {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();

        $calendar->addEvent(new Event(
            'Event 1',
            new \DateTime('Tuesday this week'),
            new \DateTime('Wednesday this week')
        ));

        $calendar->addEvent(new Event(
            'All day event',
            new \DateTime('Friday this week')
        ));

        $calendar->addEvent(new Event(
            'Entreprise',
            new \DateTime('Monday this week')
        ));
    }
}