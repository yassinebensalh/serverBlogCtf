<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Blog;
use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use function PHPUnit\Framework\isEmpty;

class BlogDataPersister implements DataPersisterInterface
{
    private $entityManager;
    private $Blog_username;

    public function __construct(EntityManagerInterface $entityManager)
    {

        $this->entityManager = $entityManager;
    }

    public function supports($data):bool
    {
        return $data instanceof Blog;
    }

    /**
     * @param Blog $data
     */
    public function persist($data)
    {

        $data->setOwner($data->getUsername()->getUsername());
        $date_current_now = new \DateTimeImmutable('@'.strtotime('+01:00'));
        $data->setDateModifiedAt($date_current_now);
        //$data->setDateCreatedAt($date_current_now);

        $this->entityManager->persist($data);
        $this->entityManager->flush();

    }

    public function remove($data)
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}