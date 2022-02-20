<?php
// api/src/Controller/CreateBookPublication.php

namespace App\Controller;

use App\Entity\Blog;
use App\Repository\BlogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

class SearchWithCategoryController extends AbstractController
{

    public function __construct()
    {

    }

    /**
     * @param Blog $data
     */
    public function __invoke(BlogRepository $blogRepository, $id)
    {
        //dd($id);
        $res =$blogRepository->displayBaseOnCategory($id);
        return ($res);
    }


}