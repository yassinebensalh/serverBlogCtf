<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Articles;
use App\Entity\UserLoginDate;
use App\Form\EditarticleperuserType;
use App\Form\EditUserPasswordType;
use App\Form\EditUserType;
use App\Form\UserType;
use App\Repository\UserLoginDateRepository;
use ContainerBKBN9dS\getEditarticleperuserTypeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Validator\Constraints\Timezone;


class HomeController extends AbstractController
{

    /**
     * @Route("/home2", name="home2")
     */
    public function index(): Response
    {
        return $this->render('emails/activation.html.twig', [
            'token' => 'yass',
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/profil", name="profil")
     */
    public function show_logged_in_user_information(UserLoginDateRepository $userlogindateRepo): Response
    {
        //search through the login date tables
        $first_indicator = $userlogindateRepo->find_dates_by_id($this->getUser());
        $week = $userlogindateRepo->find_distinct_weeks($this->getUser());

        $last_week_number = 1;
        foreach($first_indicator as $f_i){
        $last_week_number = $f_i->getWeek();
        }new JsonResponse($user->getId());
        $login_dates = $userlogindateRepo->findBy(['id_user' => $this->getUser(),
            'week' => $last_week_number
            ] );

        $date_names = array('Sunday' , 'Monday' , 'Tuesday' , 'Wednesday' , 'Thursday' , 'Friday' , 'Saturday' );
        $date_colors = array('#cc0099' , '#cc0000' , '#cccc00' , '#00cc00' , '#00cccc' , '#0033cc' , '#6600cc' );
        $date_counts = [];

        for ($i = 0; $i < count($week); $i++){
            $weeks[$i] = $week[$i]->getWeek();
        }


        for ($i = 0; $i < 7; $i++){
            $date_counts[$i] = 0;
        }

        foreach($login_dates as $log_date){
            switch($log_date->getDayName()){
                case 'Sun' :
                    $date_counts[0] += 1;
                    break;
                case 'Mon' :
                    $date_counts[1] += 1;
                    break;
                case 'Tue' :
                    $date_counts[2] += 1;
                    break;
                case 'Wed' :
                    $date_counts[3] += 1;
                    break;
                case 'Thu' :
                    $date_counts[4] += 1;
                    break;
                case 'Fri' :
                    $date_counts[5] += 1;
                    break;
                case 'Sat' :
                    $date_counts[6] += 1;
                    break;
            }
        }

        return $this->render('user/profile.html.twig', [
            'controller_name' => 'HomeController',
            'date_names' => json_encode($date_names),
            'date_colors' => json_encode($date_colors),
            'date_counts' => json_encode($date_counts),
            'weeks' => $weeks
        ]);

    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/registering-login", name="register_login_date")
     * @throws \Exception
     */
    public function register_login_date( TokenGeneratorInterface $tokenGenerator , \Swift_Mailer $mailer , UserLoginDateRepository $userLoginDateRepo): Response
    {


        $user =$this->getUser();
        $roles = $this->getUser()->getRoles();
        $set_role = ['ROLE_USER'];

        // to detemine the latest week saved inside the database login dates
        $first_indicator = $userLoginDateRepo->find_dates_by_id($this->getUser());

        if ( empty($first_indicator)){
            $last_week_number = 1;

        }
        else{
            foreach($first_indicator as $f_i){
                $last_week_number = $f_i->getWeek();
            }
        }

        $indicator = $last_week_number;

        // add the date and day to login table dates

        $res = $userLoginDateRepo->find_dates_by_id($this->getUser());


        if( empty($res)){

            $login_date_register = new UserLoginDate();

            $date_current_now = new \DateTime('@'.strtotime('+01:00'));
            $date_in_string = $date_current_now->Format('Y-m-d');
            $day= date('D', strtotime( $date_in_string));

            $login_date_register->setIdUser($user);
            $login_date_register->setDayName($day);
            $login_date_register->setLoginDate($date_current_now);
            $login_date_register->setWeek($indicator);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($login_date_register);
            $entityManager->flush();
        }
        else{
            $date_current_now = new \DateTime('@'.strtotime('+01:00'));
            $date_in_string = $date_current_now->Format('Y-m-d');
            $current_day_name= date('D', strtotime( $date_in_string));

            $day_name = $res['0']->getDayName();
            $last_login_date = $res['0']->getLoginDate();
            $indic = $res['0']->getWeek();
            $tz = new \DateTimeZone('Europe/Paris ');
            $last_login_date->setTimezone($tz);
            $diff = date_diff( $last_login_date ,$date_current_now)->days;

            //dd($diff,$indicator,$res['0']->getDayName() , $res['0']->getLoginDate(),$current_day_name);

            $indic_current_day = 0;
            $indic_last_day_registered = 0;

            if ( $current_day_name == "Mon" ){
                $indic_current_day = 1;
            }else if  ( $current_day_name == "tue" ){
                $indic_current_day = 2;
            }else if ( $current_day_name == "Wed" ){
                $indic_current_day = 3;
            }else if ( $current_day_name == "Thu" ){
                $indic_current_day = 4;
            }else if ( $current_day_name == "Fri" ){
                $indic_current_day = 5;
            }else if ( $current_day_name == "Sat" ){
                $indic_current_day = 6;
            }else if ( $current_day_name == "Sun" ){
                $indic_current_day = 7;
            }

            if ( $day_name == "Mon" ){
                $indic_last_day_registered = 1;
            }else if  ( $day_name == "Tue" ){
                $indic_last_day_registered = 2;
            }else if ( $day_name == "Wed" ){
                $indic_last_day_registered = 3;
            }else if ( $day_name == "Thu" ){
                $indic_last_day_registered = 4;
            }else if ( $day_name == "Fri" ){
                $indic_last_day_registered = 5;
            }else if ( $day_name == "Sat" ){
                $indic_last_day_registered = 6;
            }else if ( $day_name == "Sun" ){
                $indic_last_day_registered = 7;
            }

            if (  ($day_name == "Sun" && $current_day_name != "Sun" ) or ($indic_current_day < $indic_last_day_registered) or ( $diff >= 7)){
                $indicator +=1;

                $login_date_register = new UserLoginDate();

                $login_date_register->setIdUser($user);
                $login_date_register->setDayName($current_day_name);


            }
            else {
                $login_date_register = new UserLoginDate();

                $date_current_now = new \DateTime('@'.strtotime('+01:00'));
                $date_in_string = $date_current_now->Format('Y-m-d');
                $day= date('D', strtotime( $date_in_string));

                $login_date_register->setIdUser($user);
                $login_date_register->setDayName($day);


            }
            $login_date_register->setLoginDate($date_current_now);
            $login_date_register->setWeek($indicator);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($login_date_register);
            $entityManager->flush();

        }

        $highest_rank = 'ROLE_USER';
        $i = 0;
        foreach($roles as $role){
            if ( $role == 'ROLE_ADMIN' ){
                $highest_rank = 'ROLE_ADMIN';
            }
            else if ( $role == 'ROLE_EDITOR' ){
                if ( $highest_rank != 'ROLE_ADMIN'){
                    $highest_rank = 'ROLE_EDITOR';
                }
            }
            else if ( $role == 'ROLE_USER'){
                if ( $highest_rank != 'ROLE_ADMIN' && $highest_rank != 'ROLE_EDITOR'){
                    $highest_rank = 'ROLE_USER';
                }
            }

        }


        $current_date = new \DateTime('@'.strtotime('+01:00'));
        $last_login_date = $user->getLastLoginDate();

        if ( $last_login_date == null){

            $this->getUser()->setLastLoginDate($current_date);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            if ( $highest_rank == 'ROLE_ADMIN'){
                return $this->redirectToRoute('user_index');
            }
            else if ( $highest_rank == 'ROLE_EDITOR'){
                return $this->redirectToRoute('profil');
            }
            else if ( $highest_rank == 'ROLE_USER'){
                return $this->redirectToRoute('profil');
            }
        }else {
            $diff = date_diff($current_date, $last_login_date)->days;

            if ( $diff > 60  ){

                if ( $this->getUser()->getDisableToken() == null){
                    $token = $tokenGenerator->generateToken();
                    try{
                        $this->getUser()->setDisableToken($token);
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($user);
                        $entityManager->flush();
                    }catch(\Exception $e){
                        $this->addFlash('warning' , 'an error occurred : ' . $e->getMessage());
                        return $this->redirectToRoute('app_login');
                    }

                }
                else{
                    $token = $this->getUser()->getDisableToken();

                }
                // generate url or resetting the password

                $url = $this->generateUrl('reactivate_account' , ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
                // send message through email
                $message = (new \Swift_Message('REACTIVATING YOUR ACCOUNT'))
                    ->setFrom('stagesymfony@gmail.com')
                    ->setTo($this->getUser()->getEmail())
                    ->setBody(
                        '<p>Hello , </p><p> this is your request to Reactivate your account , please proceed by clicking on the link below : ' . $url . '</p>' ,
                        'text/html'
                    );
                // sending the email

                $mailer->send($message);
                //flash message of email sent confirmation

                $this->addFlash('message', 'The reactivation of your accounts email has been sent');


                //return $this->redirectToRoute('app_login');
                return $this->render('security/disabled_account.html.twig');
            }
            else{
                if ( $highest_rank == 'ROLE_ADMIN'){
                    return $this->redirectToRoute('user_index');
                }
                else if ( $highest_rank == 'ROLE_EDITOR'){
                    return $this->redirectToRoute('profil');
                }
                else if ( $highest_rank == 'ROLE_USER'){
                    return $this->redirectToRoute('profil');
                }
            }

        }


        //$disabled_token_check= $this->getUser()->getDisableToken();
       // dd($current_date , $last_login_date , $diff,$disabled_token_check);


    }
    /**
     * @Route("/denied_access", name="denied_access")

    public function index(): Response
    {

    } */

    /**
     * @Route("/reactivate_account/{token}", name="reactivate_account")
     */
    public function reactivate_account($token): Response
    {
        // search for user with the token
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['disable_token' =>$token]);

        if ( !$user ){
            $this->addFlash('danger' , 'token not existing');
            return $this->redirectToRoute('app_login');
        }
        //update date after user reactivated his account
        $current_date = new \DateTime('@'.strtotime('+00:00'));
        $user->setLastLoginDate($current_date);
        //delete token if user exist
        $user->setDisableToken(null);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        // send message that the user has been activated
        $this->addFlash('message' , 'you have RE-activated your account successfully');

        // return to user interface

        return $this->redirectToRoute('profil');
    }




    /**
     * @Route("/editusernoPassword/{id}", name="editusernp")
     */
    public function edit_logged_in_user_information_np(Request $request, User $user): Response
    {

        $cloneUser = clone $this->getUser();
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }
        else {
            $this->getUser()->setLastname($cloneUser->getLastname());
            $this->getUser()->setFirstname($cloneUser->getFirstname());
            $this->getUser()->setEmail($cloneUser->getEmail());
        }

        return $this->renderForm('user/editusernp.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }


    /**
     * @Route("/edituserwPassword/{id}", name="edituserwp")
     */
    public function edit_logged_in_user_information_wp(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $cloneUser = clone $this->getUser();
        $form = $this->createForm(EditUserPasswordType::class, $user);
        $form->handleRequest($request);
        $user->setPassword(
            $passwordEncoder->encodePassword(
                $user,
                $form->get('password')->getData()
            )
        );
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }
        else {
            $this->getUser()->setPassword($cloneUser->getPassword());
        }

        return $this->renderForm('user/edituserwp.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }







}
