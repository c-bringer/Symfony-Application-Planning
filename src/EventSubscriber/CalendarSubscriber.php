<?php

namespace App\EventSubscriber;

use App\Repository\CoursRepository;
use App\Repository\DisponibiliteRepository;
use App\Repository\SemaineRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

class CalendarSubscriber implements EventSubscriberInterface
{
    private $security;
    private $disponibiliteRepository;
    private $semaineRepository;
    private $coursRepository;

    public function __construct(DisponibiliteRepository $disponibiliteRepository,
                                SemaineRepository $semaineRepository,
                                CoursRepository $coursRepository,
                                Security $security) {
        $this->security = $security;
        $this->disponibiliteRepository = $disponibiliteRepository;
        $this->semaineRepository = $semaineRepository;
        $this->coursRepository = $coursRepository;
    }

    public static function getSubscribedEvents() {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData'
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar) {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();

        switch($filters['calendar-id']) {
            case 'intervenant-disponibilites-calendar':
                $this->calendrierDisponibilites($calendar, $start, $end, $filters);
                break;
            case 'par-intervenant-disponibilites-calendar':
                $this->calendrierDisponibilitesParIntervenant($calendar, $start, $end, $filters);
                break;
            case 'semaine-ferie-calendar':
                $this->calendrierSemaineJourFerie($calendar, $start, $end, $filters);
                break;
            case 'calendrier-general':
                $this->calendrierGeneral($calendar, $start, $end, $filters);
                break;
            case 'etudiant-calendar':
                $this->calendrierEtudiant($calendar, $start, $end, $filters);
                break;
        }
    }

    public function calendrierEtudiant(CalendarEvent $calendar, \DateTimeInterface $start, \DateTimeInterface $end, array $filters) {
        $coursList = $this->coursRepository
            ->createQueryBuilder('cours')
            ->where('cours.commenceA BETWEEN :start and :end OR cours.finiA BETWEEN :start and :end AND cours.fkGroupe IS NULL OR cours.fkGroupe = :groupe_id')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->setParameter('groupe_id', $this->security->getUser()->getFkGroupe())
            ->getQuery()
            ->getResult();

        foreach ($coursList as $cours) {
            $coursEvent = new Event(
                $cours->getLibelle() .
                ".\n Cours avec " . $cours->getFkIntervenant()->getNom() . " " . $cours->getFkIntervenant()->getPrenom() .
                ".\n Matiere " . $cours->getFkMatiere()->getLibelle(),
                $cours->getCommenceA(),
                $cours->getFiniA()
            );

            $calendar->addEvent($coursEvent);
        }

        $semaines = $this->semaineRepository
            ->createQueryBuilder('semaine')
            ->where('semaine.dateDebut BETWEEN :start and :end OR semaine.dateFin BETWEEN :start and :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult();

        foreach ($semaines as $semaine) {
            $semaineEvent = new Event(
                $semaine->getLibelle(),
                $semaine->getDateDebut(),
                $semaine->getDateFin()
            );

            $semaineEvent->setOptions([
                'id' => $semaine->getId(),
                'allDay' => true,
            ]);

            if($semaine->getLibelle() == "Entreprise") {
                $semaineEvent->addOption('backgroundColor', 'orange');
            } else if($semaine->getLibelle() == "Férié") {
                $semaineEvent->addOption('backgroundColor', 'red');
            }

            $calendar->addEvent($semaineEvent);
        }
    }

    public function calendrierGeneral(CalendarEvent $calendar, \DateTimeInterface $start, \DateTimeInterface $end, array $filters) {
        $disponibilites = $this->disponibiliteRepository
            ->createQueryBuilder('disponibilite')
            ->where('disponibilite.dateDebut BETWEEN :start and :end OR disponibilite.dateFin BETWEEN :start and :end')
//            ->join('disponibilite.fkIntervenant', 'user')
//            ->addSelect('user')
//            ->andWhere('user.id = :user_id')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
//            ->setParameter('user_id', $this->security->getUser()->getId())
            ->getQuery()
            ->getResult();

        foreach ($disponibilites as $disponibilite) {
            $disponibiliteEvent = new Event(
                "Disponibilité de " . $disponibilite->getFkIntervenant()->getNom() . " " . $disponibilite->getFkIntervenant()->getPrenom(),
                $disponibilite->getDateDebut(),
                $disponibilite->getDateFin()
            );

            $disponibiliteEvent->setOptions([
                'id' => $disponibilite->getId()
            ]);

            $calendar->addEvent($disponibiliteEvent);
        }

        $semaines = $this->semaineRepository
            ->createQueryBuilder('semaine')
            ->where('semaine.dateDebut BETWEEN :start and :end OR semaine.dateFin BETWEEN :start and :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult();

        foreach ($semaines as $semaine) {
            $semaineEvent = new Event(
                $semaine->getLibelle(),
                $semaine->getDateDebut(),
                $semaine->getDateFin()
            );

            $semaineEvent->setOptions([
                'id' => $semaine->getId(),
                'allDay' => true,
            ]);

            if($semaine->getLibelle() == "Entreprise") {
                $semaineEvent->addOption('backgroundColor', 'orange');
            } else if($semaine->getLibelle() == "Férié") {
                $semaineEvent->addOption('backgroundColor', 'red');
            }

            $calendar->addEvent($semaineEvent);
        }

        $coursList = $this->coursRepository
            ->createQueryBuilder('cours')
            ->where('cours.commenceA BETWEEN :start and :end OR cours.finiA BETWEEN :start and :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult();

        foreach ($coursList as $cours) {
            $coursEvent = new Event(
                $cours->getLibelle() .
                ".\n Cours avec " . $cours->getFkIntervenant()->getNom() . " " . $cours->getFkIntervenant()->getPrenom() .
                ".\n Matiere " . $cours->getFkMatiere()->getLibelle(),
                $cours->getCommenceA(),
                $cours->getFiniA()
            );

            $calendar->addEvent($coursEvent);
        }
    }

    public function calendrierDisponibilites(CalendarEvent $calendar, \DateTimeInterface $start, \DateTimeInterface $end, array $filters) {
        $disponibilites = $this->disponibiliteRepository
            ->createQueryBuilder('disponibilite')
            ->where('disponibilite.dateDebut BETWEEN :start and :end OR disponibilite.dateFin BETWEEN :start and :end')
            ->join('disponibilite.fkIntervenant', 'user')
            ->addSelect('user')
            ->andWhere('user.id = :user_id')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->setParameter('user_id', $this->security->getUser()->getId())
            ->getQuery()
            ->getResult();

        $semaines = $this->semaineRepository
            ->createQueryBuilder('semaine')
            ->where('semaine.dateDebut BETWEEN :start and :end OR semaine.dateFin BETWEEN :start and :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult();

        foreach ($disponibilites as $disponibilite) {
            $disponibiliteEvent = new Event(
                "Disponibilité",
                $disponibilite->getDateDebut(),
                $disponibilite->getDateFin()
            );

            $disponibiliteEvent->setOptions([
                'id' => $disponibilite->getId()
            ]);

            $calendar->addEvent($disponibiliteEvent);
        }

        foreach ($semaines as $semaine) {
            $semaineEvent = new Event(
                $semaine->getLibelle(),
                $semaine->getDateDebut(),
                $semaine->getDateFin()
            );

            $semaineEvent->setOptions([
                'id' => $semaine->getId(),
                'allDay' => true,
            ]);

            if($semaine->getLibelle() == "Entreprise") {
                $semaineEvent->addOption('backgroundColor', 'orange');
            } else if($semaine->getLibelle() == "Férié") {
                $semaineEvent->addOption('backgroundColor', 'red');
            }

            $calendar->addEvent($semaineEvent);
        }
    }

    public function calendrierSemaineJourFerie(CalendarEvent $calendar, \DateTimeInterface $start, \DateTimeInterface $end, array $filters) {
        $semaines = $this->semaineRepository
            ->createQueryBuilder('semaine')
            ->where('semaine.dateDebut BETWEEN :start and :end OR semaine.dateFin BETWEEN :start and :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult();

        foreach ($semaines as $semaine) {
            $semaineEvent = new Event(
                $semaine->getLibelle(),
                $semaine->getDateDebut(),
                $semaine->getDateFin()
            );

            $semaineEvent->setOptions([
                'id' => $semaine->getId(),
                'allDay' => true,
            ]);

            if($semaine->getLibelle() == "Entreprise") {
                $semaineEvent->addOption('backgroundColor', 'orange');
            } else if($semaine->getLibelle() == "Férié") {
                $semaineEvent->addOption('backgroundColor', 'red');
            }

            $calendar->addEvent($semaineEvent);
        }
    }

    public function calendrierDisponibilitesParIntervenant(CalendarEvent $calendar, \DateTimeInterface $start, \DateTimeInterface $end, array $filters) {
        $disponibilites = $this->disponibiliteRepository
            ->createQueryBuilder('disponibilite')
            ->where('disponibilite.dateDebut BETWEEN :start and :end OR disponibilite.dateFin BETWEEN :start and :end')
            ->join('disponibilite.fkIntervenant', 'user')
            ->addSelect('user')
            ->andWhere('user.id = :user_id')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->setParameter('user_id', $filters['userId'])
            ->getQuery()
            ->getResult();

        $semaines = $this->semaineRepository
            ->createQueryBuilder('semaine')
            ->where('semaine.dateDebut BETWEEN :start and :end OR semaine.dateFin BETWEEN :start and :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult();

        foreach ($disponibilites as $disponibilite) {
            $disponibiliteEvent = new Event(
                "Disponibilité",
                $disponibilite->getDateDebut(),
                $disponibilite->getDateFin()
            );

            $disponibiliteEvent->setOptions([
                'id' => $disponibilite->getId()
            ]);

            $calendar->addEvent($disponibiliteEvent);
        }

        foreach ($semaines as $semaine) {
            $semaineEvent = new Event(
                $semaine->getLibelle(),
                $semaine->getDateDebut(),
                $semaine->getDateFin()
            );

            $semaineEvent->setOptions([
                'id' => $semaine->getId(),
                'allDay' => true,
            ]);

            if($semaine->getLibelle() == "Entreprise") {
                $semaineEvent->addOption('backgroundColor', 'orange');
            } else if($semaine->getLibelle() == "Férié") {
                $semaineEvent->addOption('backgroundColor', 'red');
            }

            $calendar->addEvent($semaineEvent);
        }
    }
}