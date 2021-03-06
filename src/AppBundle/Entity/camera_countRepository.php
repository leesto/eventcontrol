<?php

namespace AppBundle\Entity;

use AppBundle\Entity;
use Doctrine\ORM\EntityRepository;


class camera_countRepository extends EntityRepository
{

    public function getcameradoors($id, $timestamp)
    {
        //return "hi";
        return $this->getEntityManager()->createQuery('SELECT p.id, p.timestamp, p.count_in, p.running_count_in, p.count_out, p.running_count_out, IDENTITY(p.camera_id) FROM AppBundle\Entity\camera_count p  WHERE p.camera_id = :id AND p.timestamp <= :timestamp ORDER BY p.timestamp DESC ')->setParameter('id', $id)->setParameter('timestamp', $timestamp)->setMaxResults(1)->getOneOrNullResult();
    }

    public function getcameracount($id, $endTimestamp)
    {
        $current_data = $this->getEntityManager()->createQuery('SELECT p.id, p.timestamp, p.count_in, p.running_count_in, p.count_out, p.running_count_out, IDENTITY(p.camera_id) FROM AppBundle\Entity\camera_count p  WHERE p.camera_id = :id AND p.timestamp <= :endtimestamp ORDER BY p.timestamp DESC')->setParameter('id', $id)->setParameter('endtimestamp', $endTimestamp)->setMaxResults(1)->getOneOrNullResult();

        $output['running_count_in']=$current_data['running_count_in'];
        $output['running_count_out']=$current_data['running_count_out'];
      
        return $output;

    }
    public function iscamerauptodate($id)
    {
        $date = new \DateTime();
        $date->modify('-1 minutes');
        $current_data = $this->getEntityManager()->createQuery('SELECT p.id, p.timestamp, p.count_in, p.running_count_in, p.count_out, p.running_count_out, IDENTITY(p.camera_id) FROM AppBundle\Entity\camera_count p  WHERE p.camera_id = :id AND p.timestamp >= :date  ORDER BY p.timestamp DESC')->setParameter('id', $id)->setParameter('date', $date)->setMaxResults(1)->getResult();
        if(count($current_data)>0){
            return true;
        }else{
            return false;
        }
       
        //$output['running_count_in']=$current_data[running_count_in];
        //$output['running_count_out']=$current_data[running_count_out];

        //return $output;

    }
    
    
}