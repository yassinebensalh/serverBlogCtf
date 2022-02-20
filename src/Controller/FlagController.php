<?php
// api/src/Controller/CreateBookPublication.php

namespace App\Controller;

use App\Entity\Blog;
use App\Repository\BlogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use function PHPUnit\Framework\isEmpty;

class FlagController extends AbstractController
{

    public function __construct()
    {

    }

    public function __invoke(BlogRepository $blogRepository,$id,$flag)
    {
        $decoded_id = base64_decode($id);

        $blog = null;
        $blog = $blogRepository->findOneBy([
            'flag' => $flag
        ]);
        if ( $blog == null){
            return ['state'=>'failed'];
        }
        else{
            return ['state'=>'success'];
        }
        //$res = $blogRepository->DisplayOneBlog($decoded_id);
        //dd($blog);
        //return $this->json([ 'data' => $res]);

    }


}