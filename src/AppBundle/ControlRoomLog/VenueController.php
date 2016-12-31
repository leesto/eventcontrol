<?php

namespace AppBundle\ControlRoomLog;
use AppBundle\Entity\venue;
use AppBundle\Entity\camera_count;
use AppBundle\Entity\venue_camera;
use AppBundle\Entity\skew;
use AppBundle\Entity\venue_cameraRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Query\ResultSetMapping;
use AppBundle\Form\Type\SkewType;
use AppBundle\Form\Type\VenueCameraType;

class VenueController extends Controller
{
    /**
     * @Route("/peoplecounting", name="peoplecounting");
     *
     */
    public function view(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $usr = $this->get('security.context')->getToken()->getUser();
        $venue = $em->getRepository('AppBundle\Entity\venue')->getactiveeventvenues($usr);

        //echo $venue->getName();
     
        foreach ($venue as $key => $value) {
            $venue[$key]['count'] = $em->getRepository('AppBundle\Entity\venue')->getvenuecount($value['id'], $value['event'][0]['event_log_stop_date']);
        }

        return $this->render('peoplecounting.html.twig', array('venues' => $venue));
    }

    /**
     * @Route("/venue/skew/{id}", name="skew");
     *
     */
    public function skew(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $skew = new skew();


        $timestamp = $em->getRepository('AppBundle\Entity\venue')->getvenuedoors($id);
        $em->flush();
        $venue = $em->getRepository('AppBundle\Entity\venue')->find($id);


        $venue = $em->getRepository('AppBundle\Entity\venue')->findOneBy(array('id' => $id));

        $skew->setVenueId($venue);

        $form = $this->createForm(new SkewType(), $skew);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 4) save the skew!
            $em = $this->getDoctrine()->getManager();
            
            $em->persist($skew);
            $em->flush();
            
            $skew = new skew();
            $skew->setVenueId($venue);
            $form = $this->createForm(new SkewType(), $skew);
            //return $this->redirectToRoute('skew', ['id' => $id]);
        }

        $em->flush();
        $skews = $em->getRepository('AppBundle\Entity\skew')->getvenueskew($id, $timestamp);

        return $this->render('skew.html.twig', array('skews' => $skews, 'venue' => $venue, 'form' => $form->createView()));
    }

    /**
     * @Route("/venue/camera/{id}", name="venue_camera");
     *
     */
    public function venue_camera(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        

        $venue = $em->getRepository('AppBundle\Entity\venue')->findOneBy(array('id' => $id));
        
        $venue_camera = $em->getRepository('AppBundle\Entity\venue_camera')->findBy(array('venue_id' => $id));
        
        if(!$venue_camera){
            $venue_camera = new venue_camera();
            $venue_camera->setVenueId($venue);
        }

        

        $form = $this->createForm(new VenueCameraType(), $venue_camera);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 4) save the skew!
            $em = $this->getDoctrine()->getManager();

            $em->persist($venue_camera);
            $em->flush();

            $venue_camera = new venue_camera();
            $venue->setVenueId($venue);
            $form = $this->createForm(new VenueCameraType(), $venue_camera);
            //return $this->redirectToRoute('skew', ['id' => $id]);
        }

        $em->flush();
       
        return $this->render('venue_camera.html.twig', array('venue' => $venue, 'form' => $form->createView()));
    }
    
    /**
     * @Route("/venue/doors/{id}", name="venue_doors");
     *
     */
    public function doors($id)
    {
        $em = $this->getDoctrine()->getManager();
        $venue = $em->getRepository('AppBundle\Entity\venue')->find($id);
        $venue->setDoors(new \DateTime());
        $name = $venue->getName();
        $em->flush();
        $this->get('session')->getFlashBag()->add('notice','Doors set for '.$name);
        return $this->redirectToRoute('peoplecounting');
    }
    /**
     * @Route("/venue/jsondata", name="venue_json_data");
     *
     */
    public function venue_json_data()
    {
        $em = $this->getDoctrine()->getManager();
        $usr = $this->get('security.context')->getToken()->getUser();
        $venues = $em->getRepository('AppBundle\Entity\venue')->getactiveeventvenues($usr);

        foreach ($venues as $key => $value) {
            $venues[$key]['count'] = $em->getRepository('AppBundle\Entity\venue')->getvenuecount($value['id'], $value['event'][0]['event_log_stop_date']);
            $status = $em->getRepository('AppBundle\Entity\venue')->getvenuestatus($value['id']);
            if ($status) {   $venues[$key]['status'] = "true"; }else{  $venues[$key]['status'] = "false"; }
            $status = $em->getRepository('AppBundle\Entity\venue')->getpeoplecountingstatus();
            if ($status) {   $venues['people_counting_status'] = "true"; }else{  $venues['people_counting_status'] = "false"; }
        }
        
        if ($venues)
        {
            $response = new JsonResponse();
            $response->setData($venues);

        } else {
            $response = new HttpResponse();
            $response->setContent('Hello World');
            $response->headers->set('Content-Type', 'text/plain');
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        return $response;


        //return $response;
    }

}