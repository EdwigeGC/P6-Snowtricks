<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Entity\Trick;
use App\Entity\Video;
use App\Form\PictureType;
use App\Form\VideoType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class MediaController extends AbstractController
{
    /**
     * @Route ("/picture/edit/{id}/{tricks}", name="edit_media")
     *
     * @param Trick $tricks
     * @param Picture $picture
     * @param Request $request
     * @return Response
     */
    public function editPicture (Picture $picture, Request $request, Trick $tricks) :Response
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
                dump($picture->setTricks($tricks));
            }


            /*//give a random name to the file which contains the picture
                $fileName = md5(uniqid()).'.'.$pictureFile->guessExtension();
                //save it into public/uploads/tricks
                $pictureFile->move(
                    $this->getParameter('pictures_directory').'/tricks',
                    $fileName
                );
                $picture->setFileName($fileName);*/

            $manager = $this->getDoctrine()->getManager();
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
     *
     * @Route ("/video/edit/{id}/{tricks}", name="edit_video")
     *
     * @param Trick $tricks
     * @param Request $request
     * @param Video $video
     * @return Response
     */
    public function editVideo(Request $request, Video $video, Trick $tricks): Response
    {
        $form=$this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $video->setTricks($tricks);

            $manager = $this->getDoctrine()->getManager();
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

}
