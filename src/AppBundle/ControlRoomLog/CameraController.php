<?php

namespace AppBundle\ControlRoomLog;

use AppBundle\Entity\camera;
use AppBundle\Entity\venue;
use AppBundle\Entity\venue_camera;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CameraController extends Controller
{
    /**
     * @Route("/camera/newdata/{camera_id}/{count_in}/{count_out}", name="new_camera_data")
     */
    public function newdataAction($camera_id, $count_in, $count_out)
    {
        if ($camera_id and $count_in and $count_out) {
            
            $em = $this->getDoctrine()->getManager();
            
            $camera = new camera();
            
            $camera->setCameraId($camera_id);
            $camera->setCountIn($count_in);
            $camera->setCountOut($count_out);

            $venue = $em->getRepository('AppBundle\Entity\venue_camera')->getcameravenue($camera_id);
            print_r($venue);
            //echo $venue['1'];
            //print_r($em->getRepository('AppBundle\Entity\venue_camera')->getvenuecameras($venue['1']));
            //print_r($em->getRepository('AppBundle\Entity\venue')->getvenuecount($venue['1']));
            //$venue = new venue();
            //$venue->setName("test")'
           // $venue = $em->getRepository('AppBundle\Entity\venue')->findOneBy(array('id' => $venue['1']));
            
            //$camera->setVenue($venue);

            //$venue = new venue();
            //$venue->getId($venue_id);
            //$camera->setVenue($venue);
            
            ///$em->persist($camera);
            //$em->flush();

            //return new Response('Saved new camera data with id '.$camera->getId());
        }
    }
}