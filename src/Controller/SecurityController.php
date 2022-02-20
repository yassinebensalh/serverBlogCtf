<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResetPassType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends AbstractController
{

    /**
     * @Route("/api/login", name="app_login")
     */
    public function login(): Response
    {
        $user = $this->getUser();
        if ( $user->getActivationToken()){
            $token = true;
        }else{
            $token = false;
        }

        return $this->json([

            'email' => $user->getEmail(),
            'username' => $user->getUsername(),
            'roles' => $user->getRoles(),
            'id' => $user->getId(),
            'success' => true,
            'token' =>$token,
        ]);

    }

    /**
     * @Route("/login", name="ap_login")
     */
    public function login_symf(AuthenticationUtils $authenticationUtils): Response
    {
        $user = $this->getUser();
        if ( $user != null ){
            return new RedirectResponse($this->generateUrl('profil'));
            //$authy_api = new \Authy\AuthyApi( getenv('yassine yraheb bik'));


        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = "HERE";

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/api/logout", name="app_logout", methods={"POST"})
     */
    public function logout()
    {
       // throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/denied_access", name="denied_access")
     */
    public function index(): Response
    {
        return $this->render('security/login_denied.html.twig');
    }

    /**
     * @Route("/forgotten-password", name="app_forgotten_password")
     */
    public function forgottenPass(Request $request, UserRepository $userRepo, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator): Response
    {
        // create form to retrieve email
        $form = $this->createForm(ResetPassType::class);

        //trait form
        $form->handleRequest($request);


        //if form is valid
        if ( $form->isSubmitted() && $form->isValid()){
            //extract data of the email to reset the password of
            $data = $form->getData();
            //search if the user exist with that email
            $user = $userRepo->findOneByEmail($data['email']);
            //if user doesn't exist
            if ( !$user ){
                // we send a flash message
                $this->addFlash('danger' , 'this email doesnt exist');
                return $this->redirectToRoute('app_login');
            }

            // if user exist we generate a token
            $token = $tokenGenerator->generateToken();
            try{
                $user->setResetToken($token);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            }catch(\Exception $e){
                $this->addFlash('warning' , 'an error occured : ' . $e->getMessage());
                return $this->redirectToRoute('app_login');
            }

            // generate url or resetting the password
            $url = $this->generateUrl('app_reset_password' , ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

            // send message through email
            $message = (new \Swift_Message('RESETTING PASSWORD'))
                ->setFrom('stagesymfony@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    '<p>Hello '. $user->getFirstName(). ' ' . $user->getLastName() . ', </p><p> the is your request to reset your password , please proceed by clicking on the link below : ' . $url . '</p>' ,
                    'text/html'
                );
        // sending the email
            $mailer->send($message);

            //flash message of email sent confirmation
            $this->addFlash('message', 'The reset Password email has been sent');
            return $this->redirectToRoute('app_login');
        }
        // send to email request page
        return $this->render('security/forgotten_password.html.twig', ['emailForm' => $form->createView()]);
    }

    /**
     * @Route("/Reset-password/{token}", name="app_reset_password")
     */
    public function resetPassword($token , Request $request, UserPasswordEncoderInterface $passwordEncoder): Response{
        // search for user with the token
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['reset_token' =>$token]);

        if ( !$user ){
            $this->addFlash('danger' , 'token not existing');
            return $this->redirectToRoute('app_login');
        }

        //verify if form is submitted with POST methode

        if ( $request->isMethod('POST')){
            //delete token of the user
            $user->setResetToken(null);

            //encode password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $request->request->get('password')
                )
            );
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('message' , 'password has been updated successfully');
            return $this->redirectToRoute('app_login');
        }
        else{
            return $this->render('security/reset_password.html.twig', ['token' => $token] );
        }
    }
}
