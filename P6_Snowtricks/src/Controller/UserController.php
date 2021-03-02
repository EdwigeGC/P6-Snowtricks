<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ForgotPasswordType;
use App\Form\RegistrationType;
use App\Form\AccountType;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Mailer\MailerInterface;
use App\Service\Mail;

/**
 * Provides common features needed for User management.
 *
 * @author Edwige Genty
 */
class UserController extends AbstractController
{
    /**
     * Creates 
     * 
     * @Route("/registration", name="user_registration")
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param Mail $mail
     * @param ObjectManager $manager
     * @param MailerInterface $mailer
     * 
     * @return Response
     */
    public function registration(Request $request, UserPasswordEncoderInterface $encoder,Mail $mail, ObjectManager $manager, MailerInterface $mailer): Response
    {
        $user= new User();
        
        $form=$this ->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $hash=$encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash)
                ->setValidated(false)
                ->setApiToken(md5(random_bytes(10)))
                ->setPhoto('avatar.png');
            $manager->persist($user);
            $manager->flush();

            $email= $mail->mailFormat($user, 'emails/signup.html.twig', 'Confirmation of your registration');
            $mailer->send($email);

            $this->addFlash(
                'success',
                "Information recorded! Now, check your email box to confirm registration."
            );

            return $this->redirectToRoute('home');
        }

        return $this->render('user/registration.html.twig', [
            'form'=> $form->createView()
        ]);
    }

   /**
    * Validates registration with email confirmation
    *
    * @param UserRepository $repository
    * @param string $userEmail
    * @param string $token
    * @param ObjectManager $manager
    *
    * @return Response
    *
    * @Route("/check-registration/{userEmail}/{token}", name="check_registration")
    */
    public function checkRegistration(UserRepository $repository, string $userEmail, string $token, ObjectManager $manager):Response
    {
       
        $user= $repository->findOneBy(['email'=> $userEmail]);
        if ($token != null && $token == $user->getApiToken()){
            $user->setValidated(true);
            $user->setApiToken(null);
            $manager->persist($user);
            $manager->flush();
        }

        $this->addFlash(
            'success',
            "Congratulations! Your account is now available. Sign in to continue and complete your profile."
        );
        return $this->redirectToRoute('home');
    }

    /**
     * Provides the form to change information on user's account
     *
     * @Route ("account/profile", name="account_profile")
     * @param Request $request
     * @param ObjectManager $manager
     * @param FileUploader $fileUploader
     *
     * @return Response
     */
    public function profile(Request $request, ObjectManager $manager, FileUploader $fileUploader): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $photoFile= $user->getFile();

            if( $photoFile != null){
                $user->setPhoto($fileUploader->upload($photoFile));
            }

            $user->setFile(null);
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "The information is now updated"
            );

            return $this->redirectToRoute('account_profile');
        }

        return $this->render('user/profile.html.twig',[
            'form'=> $form->createView(),
            'user'=> $user
        ]);

    }

     /** Provides form to renew forgot password
     *
     * @Route ("/password/new/{userEmail}/{token}", name="reset_password")
     *
     * @param Request $request
     * @param UserRepository $repository
     * @param string $userEmail
     * @param string $token
      * @param UserPasswordEncoderInterface $encoder
      * @param ObjectManager $manager
      *
      * @return Response
     */
    public function resetPassword(Request $request, UserRepository $repository, string $userEmail, string $token, UserPasswordEncoderInterface $encoder, ObjectManager $manager)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $repository->findOneBy(['email' => $userEmail]);
        if ($token != null && $token === $user->getApiToken()) {

            $form = $this->createForm(ResetPasswordType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $user->setApiToken(null);
                $newPassword = $form->get('password')->getData('password');
                $user->setPassword($encoder->encodePassword($user, $newPassword));
                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "New password recorded!"
                );

                return $this->redirectToRoute('home');
            }
            return $this->render('/security/reset_password.html.twig', [
                'form' => $form->createView()
            ]);
        } else {
            return $this->render('home');
        }
    }

    /**
     * Provides feature to change password for a user registered
     *
     * @Route("/password/forgotten", name="forgot_password")
     *
     * @param Request $request
     * @param UserRepository $repository
     * @param Mail $mail
     * @param MailerInterface $mailer
     * @param ObjectManager $manager
     *
     * @return Response
     */
    public function forgotPassword(Request $request, UserRepository $repository, Mail $mail, MailerInterface $mailer, ObjectManager $manager) :Response
    {
        $form=$this->createForm( ForgotPasswordType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $userEmail= $form->getdata('email');
            $repository= $this->getDoctrine()->getRepository(User::class);
            $user= $repository->findOneBy(['email'=>$userEmail]);

            if($user != null)
            {
                $user->setApiToken(md5(random_bytes(10)));
                $manager->persist($user);
                $manager->flush();

                $email= $mail->mailFormat($user, 'emails/password.html.twig', 'Forgot password process');
                $mailer->send($email);

                $this->addFlash(
                    'success',
                    "If the Email is linked to an account, you will receive an Email to change your password."
                );
                return $this->redirectToRoute('home');
            }
            else{
                $this->addFlash(
                    'success',
                    "If the Email is linked to an account, you will receive an Email to change your password."
                );
                return $this->redirectToRoute('forgot_password');
            }
        }

        return $this->render('security/forgot_password.html.twig',[
            'form'=>$form->createView()
        ]);
    }



    /**
     * Provides feature to change password for user registered and authenticated
     *
     * @Route ("newPassword/asked/{user}", name="password_process")
     *
     * @param MailerInterface $mailer
     * @param User $user
     * @param Mail $mail
     * @param ObjectManager $manager
     *
     * @return Response
     */
        public function passwordProcess (MailerInterface $mailer, User $user, Mail $mail, ObjectManager $manager) :Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user->setApiToken(md5(random_bytes(10)));
        $manager->persist($user);
        $manager->flush();

        $email= $mail->mailFormat($user, 'emails/password.html.twig', 'New password process');
        $mailer->send($email);

        $this->addFlash(
            'success',
            "An Email has been sended to your mailbox"
        );

        return $this->redirectToRoute('account_profile');
    }

}
