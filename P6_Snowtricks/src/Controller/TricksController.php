<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Form\AddTrickType;
use App\Form\EditTrickType;
use App\Repository\TrickRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * Display one trick's details
     *
     * @Route("/trick/details/{id}", name="trick_details")
     *
     * @param Request $request
     * @param TrickRepository $repository
     * @param integer $id
     * @return Response
     */
    public function trickDetails($id, TrickRepository $repository,Request $request)
    {
        dump($request);
        $repository= $this-> getDoctrine()->getRepository(Trick::class);
        $trick= $repository->findOneById($id);

        //comments
        $comment= new Comment();
        $form=$this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            dump($comment->getTricks($trick));
            $comment->setCreationDate(new \Datetime)
                    ->setUser($this->getUser())
                    ->setTricks($trick);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "New comment added !"
            );

            return $this->redirectToRoute('trick_details', [
                'id'=>$trick->getId(),
                ]);
        }

        return $this->render('tricks/trickDetails.html.twig',[
            'trick' =>$trick,
            'form'=>$form->createView()
        ]);
    }

    /**
     * Creates a new snowboard trick
     *
     * @Route("/trick/new", name="add_trick")
     * 
     * @return Response
     */
    public function create (Request $request){
        $trick= new Trick();
        
        $form=$this->createForm(AddTrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $trick  ->setCreationDate(new \Datetime)
                    ->setUser($this->getUser());

            //tricks main Picture
            $mainFile= $trick->getFileMainPicture();

            if( $mainFile != null) {
                //rename the file with random strings
                $mainName = md5(uniqid()) . '.' . $mainFile->guessExtension();
                //save it into public/uploads/tricks
                $mainFile->move(
                    $this->getParameter('pictures_directory') . '/tricks',
                    $mainName
                );
                $trick->setMainPicture($mainName);
            }

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

    /**
     * Delete a trick from the database
     * 
     * @Route("/trick/delete/{id}", name="delete_trick")
     * 
     * @param Trick $trick 
     * @return Response
     */
    public function delete(Trick $trick){
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($trick);
        $manager->flush();

        $this->addFlash(
            'success',
            "Success: the trick is deleted."
        );

        return $this->redirectToRoute('home');
    }

    /**
     * Display form to edit a trick
     *
     * @Route ("/edit/trick/{id}", name="edit_trick")
     *
     * @param Trick $trick
     * @param Request $request
     * @return Response
     */
    public function edit(Trick $trick,Request $request):Response
    {
        $form=$this->createForm(EditTrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() ){

            $trick -> setModificationDate(new\ Datetime)
                    ->setUser($this->getUser());

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($trick);
            $manager->flush();

            $this->addFlash(
                'success',
                "Modifications recorded"
            );

            return $this->redirectToRoute('trick_details',[
                'id'=> $trick->getId(),
                'trick'=>$trick->getPictures()
            ]);
        }

            return $this->render('tricks/editTrick.html.twig',[
                'form'=> $form->createView(),
                'trick'=> $trick
                ]);
    }

}
