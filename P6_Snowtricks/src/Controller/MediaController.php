<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Entity\Trick;
use App\Entity\Video;
use App\Form\PictureType;
use App\Form\VideoType;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class MediaController extends AbstractController
{
    /**
     * Edit a picture object
     *
     * @Route ("/picture/edit/{id}/{tricks}", name="edit_picture")
     *
     * @param Trick $tricks
     * @param Picture $picture
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function editPicture (Picture $picture, Request $request, Trick $tricks, ObjectManager $manager) :Response
    {
        $form=$this->createForm(PictureType::class, $picture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
                $pictureFile = $picture->getFile();
            if( $pictureFile != null){
                $pictureName = md5(uniqid()).'.'.$pictureFile->guessExtension();
                //save it into public/uploads/tricks
                $pictureFile->move(
                    $this->getParameter('pictures_directory').'/tricks',
                    $pictureName
                );
                $picture->setTricks($tricks);
                $picture->setFileName($pictureName);
            }
            $manager->persist($picture);
            $manager->flush();
            $this->addFlash(
                'success',
                "Picture edited!"
            );
            return $this->redirectToRoute('edit_trick',[
                'id'=> $picture->getTricks()->getId(),
            ]);
        }
        return $this->render('media/editPicture.html.twig',[
            'form'=> $form->createView(),
            'picture'=> $picture,
            'trick'=>$request->get('tricks')
        ]);

    }

    /**
     * Delete a Picture from the database
     *
     * @Route("/picture/delete/{id}", name="delete_picture")
     *
     * @param Picture $picture
     * @param ObjectManager $manager
     * @return Response
     */
    public function deletePicture(Picture $picture, ObjectManager $manager) :Response
    {
        $manager->remove($picture);
        $manager->flush();

        $this->addFlash(
            'success',
            "Success: the picture is deleted."
        );

        return $this->redirectToRoute('edit_trick',[
            'id'=> $picture->getTricks()->getId(),
        ]);
    }

    /**
     * Edit a video object
     *
     * @Route ("/video/edit/{id}/{tricks}", name="edit_video")
     *
     * @param Request $request
     * @param Video $video
     * @param Trick $tricks
     * @param ObjectManager $manager
     *
     * @return Response
     */
    public function editVideo (Request $request, Video $video, Trick $tricks, ObjectManager $manager): Response
    {
        $form=$this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $video->setTricks($tricks);

            $manager->persist($video);
            $manager->flush();

            $this->addFlash(
                'success',
                "Video edited!"
            );

            return $this->redirectToRoute('edit_trick',[
                'id'=> $video->getTricks()->getId(),
            ]);
        }

        return $this->render('media/editVideo.html.twig',[
            'form'=> $form->createView(),
            'picture'=> $video,
            'trick'=>$request->get('tricks')
        ]);
    }

    /**
     * Delete a Video from the database
     *
     * @Route("/video/delete/{id}", name="delete_video")
     *
     * @param Video $video
     * @param ObjectManager $manager
     * @return Response
     */
    public function deleteVideo(Video $video, ObjectManager $manager){
        $manager->remove($video);
        $manager->flush();

        $this->addFlash(
            'success',
            "Success: the picture is deleted."
        );

        return $this->redirectToRoute('edit_trick',[
            'id'=> $video->getTricks()->getId(),
        ]);
    }

}
