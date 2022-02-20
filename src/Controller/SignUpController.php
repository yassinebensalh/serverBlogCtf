<?php
// api/src/Controller/CreateBookPublication.php

namespace App\Controller;

use App\Entity\Blog;
use App\Repository\BlogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class SignUpController extends AbstractController
{

    public function __construct()
    {

    }

    public function __invoke($data,TokenGeneratorInterface $tokenGenerator , \Swift_Mailer $mailer )
    {
        $fullName = $data->getFirstname() . " " . $data->getLastname();
        $token = $tokenGenerator->generateToken();
        $data->setActivationToken($token);
        $email = $data->getEmail();
        // send message through email

        $message = (new \Swift_Message('activation de votre compte'))
            // expeditor
            ->setFrom('clubsecurinets.esprit@esprit.tn')
            ->setTo($data->getEmail())
            ->setBody(
                $this->renderView(
                    'emails/activation.html.twig',['token' => $token,
                        'fullName' =>$fullName,
                        'email' => $email
                        ]
                ),
                'text/html'
            );
        // sending the email
        $mailer->send($message);
        $current_date = new \DateTime('@'.strtotime('+01:00'));
        $data->setLastLoginDate($current_date);
        return $data;

    }


}