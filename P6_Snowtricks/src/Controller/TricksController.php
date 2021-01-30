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

        $img = new Picture();
        $img->setTitle('img1');
        $trick->getPictures()->add($img);
       
        $form=$this->createForm(AddTrickType::class, $trick);
       
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $trick  ->setCreationDate(new \Datetime)
                    ->setUser($this->getUser());
            //get the picture from the file
            $pictures= $trick->getPictures()->getFile();
dump($pictures);die;
            foreach ($pictures as $picture) {
                $picture->setTricks($trick);
            //give a random name to the file which contains the picture
                $file = md5(uniqid()).'.'.$picture->getFile()->guessExtension();
            //save it into public/uploads/tricks
            $picture->move(
                $this->getParameter('pictures_directory'),
                $file
            );
            
                //create a new instance of Picture and define its title
                $image= new Picture();
                $image->setFile($file);
                //add Picture in trick table
                $trick->addPicture($picture);
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
