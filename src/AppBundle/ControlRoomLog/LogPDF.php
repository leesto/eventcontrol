<?php

namespace AppBundle\ControlRoomLog;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;

class LogPDF extends Controller
{
    /**
    * @Route("/logPDF/{sort}");
    * @Route("/logPDF/{sort}/");
    * @Route("/logPDF/{sort}/{filter}", name="PDF_sort_filter_log"); 
    * @Route("/logPDF/{sort}/{filter}/");
    * @Route("/logPDF/{sort}/{filter}/{filter_type}", name="PDF_sort_filter_type_log");
    * @Route("/logPDF/{sort}/{filter}/{filter_type}/");
    * @Route("/logPDF/", name="full_log_pdf");
    */
    
    public function logPDFAction($sort='DESC', $filter=null, $filter_type=null)
    {
        $sort_dir = $sort == 'ASC' ? 'ASC' : 'DESC';
        $em = $this->getDoctrine()->getManager();
        
        $event = $em->getRepository('AppBundle\Entity\event')->findOneBy(
            array('event_active' => true));
        
        $em->flush();
        
        if ($event)
        {
            $eventId = $event->getId();
        } else {
            $eventId = 0;
        }
        
//        $qb = $em->createQueryBuilder(); 
//        
//        $qb
//            ->select('entry.id, entry.log_entry_open_time, entry.log_blurb, entry.location, entry.reported_by, gen.general_description, gen.general_open, gen.general_entry_closed_time, sec.security_description, sec.security_entry_closed_time, secinc.security_incident_description, secinc.severity, secinc.security_incident_colour, med.medical_entry_closed_time, medinj.medical_severity, medinj.medical_injury_description, medrsp.medical_response_description, med.nine_nine_nine_required, lost.lost_property_entry_closed_time, lost.lost_property_description')
//            ->from('AppBundle\Entity\log_entries', 'entry')
//            ->orderBy('entry.log_entry_open_time', $sort_dir)
//            ->leftJoin('AppBundle\Entity\general_log', 'gen', 'WITH', 'gen.log_entry_id = entry.id')
//            ->leftJoin('AppBundle\Entity\security_log', 'sec', 'WITH', 'sec.log_entry_id = entry.id')
//            ->leftJoin('AppBundle\Entity\security_incident_type', 'secinc', 'WITH', 'secinc.id = sec.security_incident_type')
//            ->leftJoin('AppBundle\Entity\medical_log', 'med', 'WITH', 'med.log_entry_id = entry.id')
//            ->leftJoin('AppBundle\Entity\medical_reported_injury_type', 'medinj', 'WITH', 'medinj.id = med.medical_reported_injury_type')
//            ->leftJoin('AppBundle\Entity\medical_response', 'medrsp', 'WITH', 'medrsp.id = med.medical_response')
//            ->leftJoin('AppBundle\Entity\lost_property', 'lost', 'WITH', 'lost.log_entry_id = entry.id')
//            ;
//        
//        if ($filter_type=="medical"){
//            if ($filter=="open"){
//                $qb->where($qb->expr()->andX(
//                            $qb->expr()->isNotNull('med.medical_description'),
//                            $qb->expr()->isNull('med.medical_entry_closed_time')
//                        ));
//            } elseif ($filter=="closed"){
//                $qb->where($qb->expr()->andX(
//                            $qb->expr()->isNotNull('med.medical_description'),
//                            $qb->expr()->isNotNull('med.medical_entry_closed_time')
//                        ));
//            } else {
//                $qb->where($qb->expr()->isNotNull('med.medical_description'));
//            }
//        } elseif ($filter_type=="security"){
//            if ($filter=="open"){
//                $qb->where($qb->expr()->andX(
//                            $qb->expr()->isNotNull('sec.security_description'),
//                            $qb->expr()->isNull('sec.security_entry_closed_time')
//                        ));
//            } elseif ($filter=="closed"){
//                $qb->where($qb->expr()->andX(
//                            $qb->expr()->isNotNull('sec.security_description'),
//                            $qb->expr()->isNotNull('sec.security_entry_closed_time')
//                        ));
//            } else {
//                $qb->where($qb->expr()->isNotNull('sec.security_description'));
//            }
//        } elseif ($filter_type=="general"){
//            if ($filter=="open"){
//                $qb->where($qb->expr()->andX(
//                            $qb->expr()->isNotNull('gen.general_description'),
//                            $qb->expr()->isNull('gen.general_entry_closed_time')
//                        ) );
//            } elseif ($filter=="closed"){
//                $qb->where($qb->expr()->andX(
//                            $qb->expr()->isNotNull('gen.general_description'),
//                            $qb->expr()->isNotNull('gen.general_entry_closed_time')
//                        ));
//            } else {
//                $qb->where($qb->expr()->isNotNull('gen.general_description'));
//            }
//        } elseif ($filter_type=="lost"){
//            if ($filter=="open"){
//                $qb
//                    ->where($qb->expr()->andX(
//                            $qb->expr()->isNotNull('lost.lost_property_description'),
//                            $qb->expr()->isNull('lost.lost_property_entry_closed_time')
//                        ));
//            } elseif ($filter=="closed"){
//                $qb->where($qb->expr()->andX(
//                           $qb->expr()->isNotNull('lost.lost_property_description'),
//                           $qb->expr()->isNotNull('lost.lost_property_entry_closed_time')
//                        ));
//
//            } else {
//                $qb->where($qb->expr()->isNotNull('lost.lost_property_description'));
//            }
//        }else{
//            if ($filter=="open"){
//                $qb->where($qb->expr()->orX(
//                        $qb->expr()->andX(
//                            $qb->expr()->isNotNull('gen.general_description'),
//                            $qb->expr()->isNull('gen.general_entry_closed_time')
//                             ),                            
//                        $qb->expr()->andX(
//                            $qb->expr()->isNotNull('sec.security_description'),
//                            $qb->expr()->isNull('sec.security_entry_closed_time')
//                             ),
//                        $qb->expr()->andX(
//                            $qb->expr()->isNotNull('med.medical_description'),
//                            $qb->expr()->isNull('med.medical_entry_closed_time')
//                            ),
//                        $qb->expr()->andX(
//                            $qb->expr()->isNotNull('lost.lost_property_description'),
//                            $qb->expr()->isNull('lost.lost_property_entry_closed_time')
//                            )));
//            } elseif ($filter=="closed"){
//                $qb->where($qb->expr()->andX(
//                        $qb->expr()->orX(   
//                            $qb->expr()->isNull('gen.general_description'),
//                            $qb->expr()->andX(
//                                $qb->expr()->isNotNull('gen.general_description'),
//                                $qb->expr()->isNotNull('gen.general_entry_closed_time')
//                                 )
//                            ),
//                        $qb->expr()->orX(   
//                            $qb->expr()->isNull('lost.lost_property_description'),
//                            $qb->expr()->andX(
//                                $qb->expr()->isNotNull('lost.lost_property_description'),
//                                $qb->expr()->isNotNull('lost.lost_property_entry_closed_time')
//                                 )
//                            ),                             
//                        $qb->expr()->orX(   
//                            $qb->expr()->isNull('sec.security_description'),
//                            $qb->expr()->andX(
//                                $qb->expr()->isNotNull('sec.security_description'),
//                                $qb->expr()->isNotNull('sec.security_entry_closed_time')
//                                 )
//                            ),
//                        $qb->expr()->orX(   
//                            $qb->expr()->isNull('med.medical_description'),    
//                            $qb->expr()->andX(
//                                $qb->expr()->isNotNull('med.medical_description'),
//                                $qb->expr()->isNotNull('med.medical_entry_closed_time')
//                                ))));
//            }
//        }
//        
//        if ($event){
//            $eventId = $event->getId();
//            
//            $qb->andWhere('entry.event = :eventId')
//                ->setParameter('eventId', $eventId);
//        }else{
//            $qb->andWhere('entry.log_entry_open_time <= :begin')
//                ->andWhere('entry.log_entry_open_time >= :end')
//                ->setParameter('begin', new \DateTime('2020-04-30'))
//                ->setParameter('end', new \DateTime('2014-04-25'));
//        }
//        $query = $qb->getQuery();
//        $logs = $query->getResult();
        $logs = $em->getRepository('AppBundle\Entity\log_entries')->getLogEntries($eventId, $sort, $filter, $filter_type);
        
        $html = $this->renderView('logTable.html.twig', array('logs' => $logs));
        
        $dir = $this->get('kernel')->getRootDir();
        
        //$this->get('knp_snappy.pdf')->generate('http://www.google.com', '../media/PDFlogs/pdf_test.pdf');
        
        $this->get('knp_snappy.pdf')->generateFromHtml(
            $this->renderView(
                'logTable.html.twig',
                array(
                    'logs' => $logs
                )
            ),
            '../media/PDFlogs/pdf_test2.pdf'
        );
    }
}


