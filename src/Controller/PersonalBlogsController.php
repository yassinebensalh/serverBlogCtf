<?php
// api/src/Controller/CreateBookPublication.php

namespace App\Controller;

use App\Entity\Blog;
use App\Repository\BlogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

class PersonalBlogsController extends AbstractController
{

    public function __construct()
    {

    }

    public function __invoke(BlogRepository $blogRepository)
    {
        $res = $blogRepository->DisplayBlogsOfUser($this->getUser()->getId());
        return ($res);
    }


}