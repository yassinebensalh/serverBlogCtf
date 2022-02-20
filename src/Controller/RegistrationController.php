<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer): Response
    {
        $user = $this->getUser();
        if ( $user != null ){
            return new RedirectResponse($this->generateUrl('profil'));
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        $current_date = new \DateTime('@'.strtotime('+01:00'));

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setLastLoginDate($current_date);
            // encode the plain password
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                // we create the token to activate account

            $user->setActivationToken(md5(uniqid()));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            // create message
            $message = (new \Swift_Message('activation de votre compte'))
                // expeditor
                ->setFrom('stagesymfony@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/activation.html.twig',['token' => $user->getActivationToken()]
                    ),
                    'text/html'
                );

                //send email
                $mailer->send($message);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/activation/{token}", name="activation")
     */

    public function activation($token , UserRepository $userRepo): Response{
        //verify if user has token
        $user = $userRepo->findOneBy(['activation_token' => $token]);

        //if no user exist with the token
        if ( !$user ){
            //error 404
            throw $this->createNotFoundException('this user doesnt exist');
        }

        //delete token if user exist
        $user->setActivationToken(null);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        // send message that the user has been activated
        $this->addFlash('message' , 'you have activated your account successfully');

        // return to user interface

        return $this->redirectToRoute('user_index');
    }
}
