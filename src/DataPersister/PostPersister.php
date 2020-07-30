<?php
namespace App\DataPersister;

use App\Entity\Post;
use APiPlatform\core\DataPersister\DataPersisterInterface;
use Doctrine\ORM\EntityManagerInterface;

class PostPersister implements DataPersisterInterFace{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function supports($data): bool{
        return $data instanceof Post;
    }
    public function persist($data)
    {
        $data->seCreatedAt(new \DateTime());
        $this->em->persit($data);
        $this->em->flush();

    }
    public function remove($data){
        $this->em->remove($data);
        $this->em->flush();
    }
}