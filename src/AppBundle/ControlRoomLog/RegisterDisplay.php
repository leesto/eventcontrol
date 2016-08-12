<?php

namespace AppBundle\ControlRoomLog;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Finder\Finder;

class RegisterDisplay extends Controller
{
    /**
    * @Route("/fireregister/", name="fire_register");
    */
    
    public function fireRegisterAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $event = $em->getRepository('AppBundle\Entity\event')->findOneBy(
            array('event_active' => true));
        
        $em->flush();
        
        $qb = $em->createQueryBuilder(); 
        
        $qb
            ->select('attendee.id, attendee.name, attendee.phone, attendee.email, attendee.time_in, attendee.time_out')
            ->from('AppBundle\Entity\event_control_register', 'attendee')
            ->orderBy('attendee.time_in', 'ASC')
            ;
        
        if ($event){
            $eventId = $event->getId();
            
            $qb->andWhere('attendee.event = :eventId')
                ->setParameter('eventId', $eventId);
        }else{
            $qb->andWhere('attendee.time_in <= :begin')
                ->andWhere('attendee.time_in >= :end')
                ->setParameter('begin', new \DateTime('2020-04-30'))
                ->setParameter('end', new \DateTime('2014-04-25'));
        }
       
        $query = $qb->getQuery();
        $attendees = $query->setMaxResults(30)
                            ->getResult();
        return $this->render('fireRegister.html.twig', array('attendees' => $attendees));
    }
    
    /**
    * @Route("/PDFfireregister/", name="pdf_fire_register");
    * 
    */
    
    public function pdfFireRegisterAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $event = $em->getRepository('AppBundle\Entity\event')->findOneBy(
            array('event_active' => true));
        
        $em->flush();
        
        $qb = $em->createQueryBuilder(); 
        
        $qb
            ->select('attendee.id, attendee.name, attendee.phone, attendee.email, attendee.time_in, attendee.time_out')
            ->from('AppBundle\Entity\event_control_register', 'attendee')
            ->orderBy('attendee.time_in', 'ASC')
            ;
        
        if ($event){
            $eventId = $event->getId();
            
            $qb->andWhere('attendee.event = :eventId')
                ->setParameter('eventId', $eventId);
        }else{
            $qb->andWhere('attendee.time_in <= :begin')
                ->andWhere('attendee.time_in >= :end')
                ->setParameter('begin', new \DateTime('2020-04-30'))
                ->setParameter('end', new \DateTime('2014-04-25'));
        }
       
        $query = $qb->getQuery();
        $attendees = $query->setMaxResults(30)
                            ->getResult();
        
        //timestamp for file
        $dateDIR = date("Ymd-His");
        //Event Directory
        $eventDIR = $event->getId().'-'.$event->getName();

        //See if an old fire register can be found
        $finder = new Finder();
        $finder->in('../media/PDFReports/'.$eventDIR);
        $finder->files()->name('Fire Register *.pdf');
        
        $fs = new Filesystem();
        
        foreach ($finder as $file) {
            try{
                $oldFilename = $file->getFilename();
                $OldFileExists = $fs->exists('../media/PDFReports/'.$eventDIR.'/'.$oldFilename);
                if($OldFileExists){
                    $fs->remove('../media/PDFReports/'.$eventDIR.'/'.$oldFilename);
                }
            } catch (IOExceptionInterface $e) {

            }
        }

        //Filename
        $ReportFilename = 'Fire Register '.$dateDIR.'.pdf';

        //Generate Fire Register
        $this->get('knp_snappy.pdf')->generateFromHtml(
            $this->renderView(
                        'pdfRegister.html.twig',
                        array(
                            'attendees' => $attendees,
                            'event' => $event, 
                        )
                    ),
            '../media/PDFReports/'.$eventDIR.'/'.$ReportFilename
        );

        //log file in event system
        $em->flush();
        $event = $em->getRepository('AppBundle\Entity\event')->findOneBy(array('id' => $eventId));
        $event->setEventReportFilename($ReportFilename);
        $event->setEventReportRunDate(new \DateTime());
        $em->persist($event);
        $em->flush();

        //return $this->render('fireRegister.html.twig', array('attendees' => $attendees));
        return new BinaryFileResponse('../media/PDFReports/'.$eventDIR.'/'.$ReportFilename);
    }
}


