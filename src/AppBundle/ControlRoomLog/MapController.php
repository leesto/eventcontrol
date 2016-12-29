<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\ControlRoomLog;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;

/**
 * Description of MapController
 *
 * @author Nick
 */
class MapController extends Controller 
{
    /**
    * @Route("/map.{_format}", name="map_pdf");
    * @Route("/map/");
    * @Route("/map", name="full_map");
    * @Route("/map/{filter}", name="map_filter");
    * @Route("/map/{filter}/");
    * @Route("/map/{filter}/{filter_type}", name="map_filter_type");
    * @Route("/map/{filter}/{filter_type}/");
    */
    
     public function mapAction($_format="html", $filter=null, $filter_type=null)
    {
        $em = $this->getDoctrine()->getManager();
        
        $usr = $this->get('security.context')->getToken()->getUser();
        $operatorId = $usr->getId();
        
        $event = $em->getRepository('AppBundle\Entity\user_events')->getActiveEvent($operatorId);
        
        $overlay = $event->getOverlayImageName();
        
        $em->flush();
        
        if ($overlay)
        {
            $eventId = $event->getId();
            $latLong = $latLong = explode(",", $event->getEventLatLong());
            $latitude = $latLong[0];
            $longitude = $latLong[1];
            $NEbound = $event->getNorthEastBounds();
            $SWbound = $event->getSouthWestBounds();
            return $this->render('map.html.twig', array('event' => $event, 'overlayFileName' => $overlay, 'NEbound' => $NEbound,'SWbound' => $SWbound, 'latitude' => $latitude, 'longitude' => $longitude, 'filter' => $filter, 'filter_type' => $filter_type));
        } else {
            return $this->redirectToRoute('full_log');
        }
    }
    
     /**
     * @Route("/mapjsondata/");
     * @Route("/mapjsondata/{filter}", name="map_filter_json_data");
     * @Route("/mapjsondata/{filter}/");
     * @Route("/mapjsondata/{filter}/{filter_type}", name="map_filter_type_json_data");
     * @Route("/mapjsondata/{filter}/{filter_type}/", name="map_json_data");
     * @Route("/mapjsondata", name="map_json_data");
     *
     */
    public function map_json_data($filter=null, $filter_type=null)
    {
        $em = $this->getDoctrine()->getManager();
        
        $usr = $this->get('security.context')->getToken()->getUser();
        $operatorId = $usr->getId();
        
        $event = $em->getRepository('AppBundle\Entity\user_events')->getActiveEvent($operatorId);
        
        $em->flush();
        
        $sort_dir = 'DESC';
        
        if ($event)
        {
            $eventId = $event->getId();
        } else {
            $eventId = 0;
        }
        
        $em->flush();
        
        $qb = $em->createQueryBuilder(); 
        
        $qb
            ->select('entry.id, entry.log_entry_open_time, entry.log_update_timestamp, entry.log_blurb, entry.location, entry.latitude, entry.longitude, entry.reported_by, entry.park_alert, gen.general_description, gen.general_open, gen.general_entry_closed_time, sec.security_description, sec.security_entry_closed_time, secinc.security_incident_description, secinc.severity, secinc.security_incident_colour, med.medical_entry_closed_time, medinj.medical_severity, medinj.medical_injury_description, medrsp.medical_response_description, med.nine_nine_nine_required, lost.lost_property_entry_closed_time, lost.lost_property_description')
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
            if ($filter=="open"){
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
        
        if ($event){
            $eventId = $event->getId();
            
            $qb->andWhere('entry.event = :eventId')
                ->setParameter('eventId', $eventId);
            
        }else{
            $qb->andWhere('entry.log_entry_open_time <= :begin')
                ->andWhere('entry.log_entry_open_time >= :end')
                ->setParameter('begin', new \DateTime('2020-04-30'))
                ->setParameter('end', new \DateTime('2014-04-25'));
        }
        $query = $qb->getQuery();
        $logs = $query->getResult();
        
        //Now convert the data from logs in to GeoJson formatting.
        $data['type'] = "FeatureCollection";
        $data['features'] = array();
        
        foreach ($logs as $log)
        {
            if($log['latitude'] != null)
            {
                if (min($log['severity'], $log['medical_severity']) == 1){
                    $colour = "#E50D00";
                } elseif(min($log['severity'], $log['medical_severity']) == 2) {
                    $colour = "#DF7200";
                } elseif(min($log['severity'], $log['medical_severity']) == 3) {
                    $colour = "#D9D100";
                } elseif(min($log['severity'], $log['medical_severity']) == 4) {
                    $colour = "#7CD300";
                } elseif(min($log['severity'], $log['medical_severity']) == 5) {
                    $colour = "#1CCE00";
                } else {
                    $colour = "#ff8080"
                }
                    
                $logFeature = ['type' => "Feature", 'properties' => ["marker-color" => $colour, "marker-size" => "medium", "marker-symbol"=> "", "id" => $log['id']], 'geometry' => ["type" => "point", "coordinates" => [floatval($log['longitude']), floatval($log['latitude'])]]];
                array_push($data['features'],$logFeature);
            }
        }
        
        $response = new JsonResponse();
        
        if ($logs)
        {
            $response->setData($data);
        } else {
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
        }
        return $response;
    }
}
