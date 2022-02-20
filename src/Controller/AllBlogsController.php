<?php
// api/src/Controller/CreateBookPublication.php

namespace App\Controller;

use App\Entity\Blog;
use App\Repository\BlogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

class AllBlogsController extends AbstractController
{

    public function __construct()
    {

    }

    public function __invoke(BlogRepository $blogRepository)
    {
        //$res =$blogRepository->displayBaseOnCategory();
        //dd(count($res));
        // dd($res->getCategory());

       // dd($blogRepository->DisplayAllBlogs());
        $res = $blogRepository->DisplayAllBlogs();
        return $res;
        //return $this->json([ 'data' => $res]);

    }


}