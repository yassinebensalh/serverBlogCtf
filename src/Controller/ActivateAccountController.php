<?php
// api/src/Controller/CreateBookPublication.php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\User;
use App\Repository\BlogRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

class ActivateAccountController extends AbstractController
{

    public function __construct()
    {

    }

    public function __invoke($token, UserRepository $repository)
    {
        $user = null;
        $user = $repository->find_user_by_token($token);
        if ($user == null){
            return $user;
        }else{
            $USER = new User();
            $USER = $user[0];
        }
        $USER->setActivationToken(null);
        return $USER;
    }


}