<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
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
            ->to('edwige.gentymail@gmail.com')
            ->subject('test')
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
    * @param [type] $username
    * @param [type] $token
    * @return Response
    *
    * @Route("/check-registration/{username}/{token}", name="check_registration")
    */
    public function checkRegistration(UserRepository $repository, $username, $token){
       
        $user= $repository->findOneByUsername($username);
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
}
