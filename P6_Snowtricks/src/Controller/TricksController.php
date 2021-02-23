<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Form\AddTrickType;
use App\Form\EditTrickType;
use App\Repository\TrickRepository;
use App\Service\UploadsPicture;
use Doctrine\Persistence\ObjectManager;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TricksController extends AbstractController
{
    /**
     * Display home page of the website
     *
     * @Route("/home", name="home")
     * @Route("/")
     *
     * @param TrickRepository $repository
     *
     * @return Response
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
     * @param integer $id
     * @param Request $request
     * @param TrickRepository $repository
     * @param ObjectManager $manager
     *
     * @return Response
     */
    public function trickDetails($id, TrickRepository $repository, Request $request, ObjectManager $manager)
    {
        $trick= $repository->findOneById($id);

        //comments
        $comment= new Comment();
        $form=$this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $comment->setCreationDate(new \Datetime)
                    ->setUser($this->getUser())
                    ->setTricks($trick);

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
            'form'=>$form->createView(),
            'trick' =>$trick,
        ]);
    }

    /**
     * Creates a new snowboard trick. It uses "upload" function from App\Service\FileUploader to rename file uploaded and move them in Picture_Directory
     *
     * @Route("/trick/new", name="add_trick")
     *
     * @param Request $request
     * @param FileUploader $fileUploader
     * @param ObjectManager $manager
     * 
     * @return Response
     */
    public function create (Request $request, FileUploader $fileUploader, ObjectManager $manager) :Response
    {
        $trick= new Trick();
        
        $form=$this->createForm(AddTrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $trick  ->setCreationDate(new \Datetime)
                    ->setUser($this->getUser());

            //tricks main Picture
            $mainPictureFile= $trick->getFileMainPicture();
            if( $mainPictureFile != null) {
                $trick->setMainPicture($fileUploader->upload($mainPictureFile));
            }
            //pictures
            foreach ($trick->getPictures() as $picture) {
                $picture->setFileName($fileUploader->upload($picture->getFile()))
                    ->setTricks($trick);
            }
            //videos
            foreach ($trick->getVideos() as $video){
                $video->setTricks($trick);
            }

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
     *@param ObjectManager $manager
     *
     * @return Response
     */
    public function delete(Trick $trick, ObjectManager $manager): Response
    {
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
     * @param ObjectManager $manager
     *
     * @return Response
     */
    public function edit(Trick $trick,Request $request, ObjectManager $manager):Response
    {
        $form=$this->createForm(EditTrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() ){

            $trick -> setModificationDate(new\ Datetime)
                    ->setUser($this->getUser());

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
