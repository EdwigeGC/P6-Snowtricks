<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ForgotPasswordType;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * Displays login form and provides access to user's account
     * 
     * @Route("/login", name="user_login")
     * 
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * Logout the user session
     * 
     * @Route("/logout", name="user_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * Provides feature to change password for a user registered
     *
     * @Route("/password/forgotten", name="forgot_password")
     *
     * @param Request $request
     * @param UserRepository $repository
     *
     * @return Response
     */
    public function forgotPassword(Request $request, UserRepository $repository, MailerInterface $mailer) :Response
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
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                $email = (new TemplatedEmail())
                    ->from('no.reply@snowtricks.com')
                    ->to($user->getEmail())
                    ->subject('Change your password')
                    ->htmlTemplate('emails/password.html.twig')
                    ->context([
                        'userEmail'=> $user->getEmail(),
                        'token' => $user->getApiToken()
                    ]);

                $mailer->send($email);

                $this->addFlash(
                    'success',
                    "If the Email is linked to an account, you will receive an Email to change your password."
                );

                return $this->redirectToRoute('forgot_password');
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


}
