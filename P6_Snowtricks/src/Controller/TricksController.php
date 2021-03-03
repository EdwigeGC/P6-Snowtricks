<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\AddTrickType;
use App\Form\CommentType;
use App\Form\EditTrickType;
use App\Repository\TrickRepository;
use App\Service\FileUploader;
use App\Service\Paginator;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Provides common features needed for Trick management.
 *
 * @author Edwige Genty
 */
class TricksController extends AbstractController
{
    /**
     * Display home page of the website.
     *
     * @param int $page
     * @param Paginator $paginator
     *
     * @Route("/home/{page<\d+>?1}", name="home")
     * @Route("/{page<\d+>?1}")
     *
     * @return Response
     */
    public function home(int $page, Paginator $paginator): Response
    {
        $paginator->setEntityClass(Trick::class)
                    ->setPage($page);

        return $this->render('tricks/tricksList.html.twig', [
            'controller_name' => 'TricksController',
            'paginator' => $paginator,
        ]);
    }

    /**
     * Display one trick's details.
     *
     * @Route("/trick/details/{id}/{page<\d+>?1}", name="trick_details")
     *
     * @param $id
     * @param TrickRepository $repository
     * @param Request $request
     * @param ObjectManager $manager
     * @param Paginator $paginator
     * @param int $page
     *
     * @return Response
     */
    public function trickDetails($id, TrickRepository $repository, Request $request, ObjectManager $manager, Paginator $paginator, int $page): Response
    {
        $trick = $repository->findOneById($id);

        //displays comments
        $paginator->setEntityClass(Comment::class)
                    ->setPage($page)
                    ->setOrderBy(['creationDate' => 'desc'])
                    ->setFilterBy(['tricks' => $id]);

        //creates form to write and save a comment
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreationDate(new \Datetime())
                    ->setUser($this->getUser())
                    ->setTricks($trick);

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                'New comment added !'
            );

            return $this->redirectToRoute('trick_details', [
                'id' => $trick->getId(),
                ]);
        }

        return $this->render('tricks/trickDetails.html.twig', [
            'form' => $form->createView(),
            'trick' => $trick,
            'paginator' => $paginator,
        ]);
    }

    /**
     * Creates a new snowboard trick. It uses "upload" function from App\Service\FileUploader to rename file uploaded and move them in Picture_Directory.
     *
     * @Route("/trick/new", name="add_trick")
     *
     * @param Request $request
     * @param FileUploader $fileUploader
     * @param ObjectManager $manager
     * @return Response
     */
    public function create(Request $request, FileUploader $fileUploader, ObjectManager $manager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $trick = new Trick();

        $form = $this->createForm(AddTrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setCreationDate(new \Datetime())
                    ->setUser($this->getUser());

            //tricks main Picture
            $mainPictureFile = $trick->getFileMainPicture();
            if (null != $mainPictureFile) {
                $trick->setMainPicture($fileUploader->upload($mainPictureFile));
            }
            //pictures
            foreach ($trick->getPictures() as $picture) {
                $picture    ->setFileName($fileUploader->upload($picture->getFile()))
                            ->setTricks($trick);
            }
            //videos
            foreach ($trick->getVideos() as $video) {
                $video->setTricks($trick);
            }

            $manager->persist($trick);
            $manager->flush();

            $this->addFlash(
                'success',
                'New trick added !'
            );

            return $this->redirectToRoute('home');
        }

        return $this->render('tricks/addTrick.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete a trick from the database.
     *
     * @Route("/trick/delete/{id}", name="delete_trick")
     *
     * @param Trick $trick
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Trick $trick, ObjectManager $manager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if($this->isGranted('POST_EDIT', $trick)){
        $manager->remove($trick);
        $manager->flush();

        $this->addFlash(
            'success',
            'Success: the trick is deleted.'
        );

        return $this->redirectToRoute('home');
        }
    else{
        $this->addFlash(
            'danger',
            "Access denied. You are not the author of this trick."
        );

        return $this->redirectToRoute('home');
        }
    }

    /**
     * Display form to edit a trick.
     *
     * @Route ("/edit/trick/{id}", name="edit_trick")
     *
     * @param Trick $trick
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function edit(Trick $trick, Request $request, ObjectManager $manager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if($this->isGranted('POST_EDIT', $trick)){
            $form=$this->createForm(EditTrickType::class, $trick);
            $form->handleRequest($request);

            if ($form->isSubmitted() ){

                $trick -> setModificationDate(new\ Datetime);

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
        else{
            $this->addFlash(
                'danger',
                "Access denied. You are not the author of this trick."
            );

            return $this->redirectToRoute('home');
        }

    }
}
