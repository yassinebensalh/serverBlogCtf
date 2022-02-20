<?php
// api/src/Controller/CreateBookPublication.php

namespace App\Controller;

use App\Entity\Blog;
use App\Repository\BlogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

class oneBlogController extends AbstractController
{

    public function __construct()
    {

    }

    public function __invoke(BlogRepository $blogRepository,$id)
    {
        $decoded_id = base64_decode($id);
        //dd($decoded_id);
        $res = $blogRepository->DisplayOneBlog($decoded_id);
        //dd($res);
        return $res;
        //return $this->json([ 'data' => $res]);

    }


}