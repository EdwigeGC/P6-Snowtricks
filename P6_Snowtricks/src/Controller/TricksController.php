<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\Picture;
use App\Form\AddTrickType;
use App\Repository\TrickRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class TricksController extends AbstractController
{
    /**
     * Display home page of the website
     * @Route("/home", name="home")
     * @Route("/")
     */
    public function home(TrickRepository $repository): Response
    {
        $tricks= $repository->findAll();
        return $this->render('tricks/tricksList.html.twig', [
            'controller_name' => 'TricksController',
            'tricks' => $tricks,
        ]);
    }

    /**
     * Creates a new snowboard trick
     *
     * @Route("/trick/new", name="add_trick")
     * 
     * @return Response
     */
    public function create(Request $request){
        $trick= new Trick();
        
        $form=$this->createForm(AddTrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $trick  ->setCreationDate(new \Datetime)
                    ->setUser($this->getUser());

            foreach ($trick->getPictures() as $picture) {
                $picture->setTricks($trick);
                $pictureFile = $picture->getFile();
                //give a random name to the file which contains the picture
                $fileName = md5(uniqid()).'.'.$pictureFile->guessExtension();
                //save it into public/uploads/tricks
                $pictureFile->move(
                    $this->getParameter('pictures_directory').'/tricks',
                $fileName
            );
                $picture->setFileName($fileName);
            }

            foreach ($trick->getVideos() as $video){
                $video->setTricks($trick);
            }

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($trick);
            $manager->flush();

            $this->addFlash(
                'success',
                "New trick added !"
            );
    
            return $this->redirectToRoute('home');
        }
        return $this->render('tricks/addTrick.html.twig', [
            'form'=> $form->createView(),
        ]);
       
    }

}
