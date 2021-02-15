<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Form\AccountType;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

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
     * @return Response
     */
    public function registration(Request $request, UserPasswordEncoderInterface $encoder,MailerInterface $mailer): Response
    {
        $user= new User();
        
        $form=$this ->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $hash=$encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash)
                ->setValidated(false)
                ->setApiToken(md5(random_bytes(10)));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $email= (new TemplatedEmail())
            ->from('no.reply@snowtricks.com')
            ->to($user->getEmail())
            ->subject('Confirmation of your registration')
            ->htmlTemplate('emails/signup.html.twig')
            ->context([
                'username' => $user->getUsername(),
                'token' => $user->getApiToken()
            ]);

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
    * @param string $username
    * @param string $token
    * @return Response
    *
    * @Route("/check-registration/{username}/{token}", name="check_registration")
    */
    public function checkRegistration(UserRepository $repository, $username, $token){
       
        $user= $repository->findOneBy(['username'=> $username]);
        if ($token != null && $token == $user->getApiToken()){
            $user->setValidated(true);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }

        $this->addFlash(
            'success',
            "Congratulations! Your account is now available. Sign in to continue."
        );
        return $this->redirectToRoute('home');
    }

    /**
     * Provides the form to change information on user's account
     *
     * @Route ("account/profile", name="account_profile")
     * @param Request $request
     * @return Response
     */
    public function profile(Request $request): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $photoFile= $user->getFile();

            if( $photoFile != null){
                $photoName = md5(uniqid()).'.'.$photoFile->guessExtension();
                //save it into public/uploads/profile
                $photoFile->move(
                    $this->getParameter('pictures_directory').'/profile',
                    $photoName
                );
                $user->setPhoto($photoName);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "The information is now updated"
            );

            return $this->redirectToRoute('home');
        }

        return $this->render('user/profile.html.twig',[
            'form'=> $form->createView(),
            'user'=> $user
        ]);

    }

     * Provides form to create a new password
     *
     * @Route ("/password/new/{userEmail}/{token}", name="reset_password")
     *
     * @param Request $request
     * @param UserRepository $repository
     * @param string $userEmail
     * @param string $token
     */
    public function resetPassword(Request $request, UserRepository $repository, $userEmail, $token, UserPasswordEncoderInterface $encoder)
    {
        $user= $repository->findOneBy(['email'=>$userEmail]);
        if ($token != null && $token === $user->getApiToken()) {

            $form=$this->createForm( ResetPasswordType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted () && $form->isValid())
            {
                $user->setApiToken(null);
                $newPassword=$form->get('password')->getData('password');
                $user->setPassword($encoder->encodePassword($user, $newPassword));
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash(
                    'success',
                    "New password recorded!"
                );

                return $this->redirectToRoute('home');
            }
            return $this->render('/security/reset_password.html.twig', [
                'form'=> $form->createView()
            ]);
        }
        else {
            return $this->render('home');
        }
    }
}
