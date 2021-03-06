<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

/**
 * Description of log_entriesRepository
 *
 * @author Nick
 */

use AppBundle\Entity;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

class log_entriesRepository extends EntityRepository
{
    public function getLocationLookup($event, $location=null)
    {
        if ($location){
            return $this->getEntityManager()->createQuery('SELECT DISTINCT log_entry.location FROM AppBundle\Entity\log_entries log_entry WHERE log_entry.event = :event AND log_entry.location LIKE :location')->setParameter('event', $event)->setParameter('location', '%'.$location.'%')->getResult();
        } else {
            return $this->getEntityManager()->createQuery('SELECT DISTINCT log_entry.location FROM AppBundle\Entity\log_entries log_entry WHERE log_entry.event = :event')->setParameter('event', $event)->getResult();
        }
    }
    
    public function getReportedByLookup($event, $reported=null)
    {
        if ($reported){
            return $this->getEntityManager()->createQuery('SELECT DISTINCT log_entry.reported_by FROM AppBundle\Entity\log_entries log_entry WHERE log_entry.event = :event AND log_entry.reported_by LIKE :reported')->setParameter('event', $event)->setParameter('reported', '%'.$reported.'%')->getResult();
        } else {
            return $this->getEntityManager()->createQuery('SELECT DISTINCT log_entry.reported_by FROM AppBundle\Entity\log_entries log_entry WHERE log_entry.event = :event')->setParameter('event', $event)->getResult();
        }
        
    }
    
    public function getLogEntries($eventId, $sort='DESC', $filter=null, $filter_type=null)
    {
        $sort_dir = $sort == 'ASC' ? 'ASC' : 'DESC';
        $em = $this->getEntityManager();
        
        if($eventId != 0)
        {
            $qb = $em->createQueryBuilder(); 
        
            $qb
                ->select('entry.id, entry.ref, entry.log_entry_open_time, entry.log_update_timestamp, entry.log_blurb, entry.geolocated, entry.latitude, entry.longitude, entry.location, entry.reported_by, entry.park_alert, gen.general_description, gen.general_open, gen.general_entry_closed_time, sec.security_description, sec.security_entry_closed_time, secinc.security_incident_description, secinc.severity, secinc.security_incident_colour, med.medical_description, med.medical_entry_closed_time, medinj.medical_severity, medinj.medical_injury_description, medrsp.medical_response_description, med.nine_nine_nine_required, lost.lost_property_entry_closed_time, lost.lost_property_description')
                ->from('AppBundle\Entity\log_entries', 'entry')
                ->orderBy('entry.log_entry_open_time', $sort_dir)
                ->leftJoin('AppBundle\Entity\general_log', 'gen', 'WITH', 'gen.log_entry_id = entry.id')
                ->leftJoin('AppBundle\Entity\security_log', 'sec', 'WITH', 'sec.log_entry_id = entry.id')
                ->leftJoin('AppBundle\Entity\security_incident_type', 'secinc', 'WITH', 'secinc.id = sec.security_incident_type')
                ->leftJoin('AppBundle\Entity\medical_log', 'med', 'WITH', 'med.log_entry_id = entry.id')
                ->leftJoin('AppBundle\Entity\medical_reported_injury_type', 'medinj', 'WITH', 'medinj.id = med.medical_reported_injury_type')
                ->leftJoin('AppBundle\Entity\medical_response', 'medrsp', 'WITH', 'medrsp.id = med.medical_response')
                ->leftJoin('AppBundle\Entity\lost_property', 'lost', 'WITH', 'lost.log_entry_id = entry.id')
                ->leftJoin('AppBundle\Entity\User', 'user', 'WITH', 'user.id = entry.operator')
                ->leftJoin('AppBundle\Entity\event', 'event', 'WITH', 'event.id = entry.event')
                ->where('event.id = :eventId')
                ->setParameter('eventId', $eventId)
                ;

            if ($filter_type=="medical"){
                if ($filter=="open"){
                    $qb->andWhere($qb->expr()->andX(
                                $qb->expr()->isNotNull('med.medical_description'),
                                $qb->expr()->isNull('med.medical_entry_closed_time')
                            ));
                } elseif ($filter=="closed"){
                    $qb->andWhere($qb->expr()->andX(
                                $qb->expr()->isNotNull('med.medical_description'),
                                $qb->expr()->isNotNull('med.medical_entry_closed_time')
                            ));
                } else {
                    $qb->andWhere($qb->expr()->isNotNull('med.medical_description'));
                }
            } elseif ($filter_type=="security"){
                if ($filter=="open"){
                    $qb->andWhere($qb->expr()->andX(
                                $qb->expr()->isNotNull('sec.security_description'),
                                $qb->expr()->isNull('sec.security_entry_closed_time')
                            ));
                } elseif ($filter=="closed"){
                    $qb->andWhere($qb->expr()->andX(
                                $qb->expr()->isNotNull('sec.security_description'),
                                $qb->expr()->isNotNull('sec.security_entry_closed_time')
                            ));
                } else {
                    $qb->andWhere($qb->expr()->isNotNull('sec.security_description'));
                }
            } elseif ($filter_type=="general"){
                if ($filter=="open"){
                    $qb->andWhere($qb->expr()->andX(
                                $qb->expr()->isNotNull('gen.general_description'),
                                $qb->expr()->isNull('gen.general_entry_closed_time')
                            ) );
                } elseif ($filter=="closed"){
                    $qb->andWhere($qb->expr()->andX(
                                $qb->expr()->isNotNull('gen.general_description'),
                                $qb->expr()->isNotNull('gen.general_entry_closed_time')
                            ));
                } else {
                    $qb->andWhere($qb->expr()->isNotNull('gen.general_description'));
                }
            } elseif ($filter_type=="lost"){
                if ($filter=="open"){
                    $qb
                        ->andWhere($qb->expr()->andX(
                                $qb->expr()->isNotNull('lost.lost_property_description'),
                                $qb->expr()->isNull('lost.lost_property_entry_closed_time')
                            ));
                } elseif ($filter=="closed"){
                    $qb->andWhere($qb->expr()->andX(
                               $qb->expr()->isNotNull('lost.lost_property_description'),
                               $qb->expr()->isNotNull('lost.lost_property_entry_closed_time')
                            ));

                } else {
                    $qb->andWhere($qb->expr()->isNotNull('lost.lost_property_description'));
                }
            }else{
                if ($filter == "open"){
                    $qb->andWhere($qb->expr()->orX(
                            $qb->expr()->andX(
                                $qb->expr()->isNotNull('gen.general_description'),
                                $qb->expr()->isNull('gen.general_entry_closed_time')
                                 ),                            
                            $qb->expr()->andX(
                                $qb->expr()->isNotNull('sec.security_description'),
                                $qb->expr()->isNull('sec.security_entry_closed_time')
                                 ),
                            $qb->expr()->andX(
                                $qb->expr()->isNotNull('med.medical_description'),
                                $qb->expr()->isNull('med.medical_entry_closed_time')
                                ),
                            $qb->expr()->andX(
                                $qb->expr()->isNotNull('lost.lost_property_description'),
                                $qb->expr()->isNull('lost.lost_property_entry_closed_time')
                                )));
                } elseif ($filter=="closed"){
                    $qb->andWhere($qb->expr()->andX(
                            $qb->expr()->orX(   
                                $qb->expr()->isNull('gen.general_description'),
                                $qb->expr()->andX(
                                    $qb->expr()->isNotNull('gen.general_description'),
                                    $qb->expr()->isNotNull('gen.general_entry_closed_time')
                                     )
                                ),
                            $qb->expr()->orX(   
                                $qb->expr()->isNull('lost.lost_property_description'),
                                $qb->expr()->andX(
                                    $qb->expr()->isNotNull('lost.lost_property_description'),
                                    $qb->expr()->isNotNull('lost.lost_property_entry_closed_time')
                                     )
                                ),                             
                            $qb->expr()->orX(   
                                $qb->expr()->isNull('sec.security_description'),
                                $qb->expr()->andX(
                                    $qb->expr()->isNotNull('sec.security_description'),
                                    $qb->expr()->isNotNull('sec.security_entry_closed_time')
                                     )
                                ),
                            $qb->expr()->orX(   
                                $qb->expr()->isNull('med.medical_description'),    
                                $qb->expr()->andX(
                                    $qb->expr()->isNotNull('med.medical_description'),
                                    $qb->expr()->isNotNull('med.medical_entry_closed_time')
                                    ))));
                }
            }

            $query = $qb->getQuery();
            $logs = $query->getResult();
        } else {
            $logs = null;
        }
        
        return $logs;
    }
}
