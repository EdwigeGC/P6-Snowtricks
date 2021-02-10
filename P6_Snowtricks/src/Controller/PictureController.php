<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Form\PictureType;
use App\Entity\Trick;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class PictureController extends AbstractController
{
    /**
     * @Route ("/picture/edit/{id}/{tricks}", name="edit_media")
     *
     * @param Picture $picture
     * @param Request $request
     * @return Response
     */
    public function editMedia (Picture $picture, Request $request) :Response
    {
        $form=$this->createForm(PictureType::class, $picture);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

                $pictureFile = $picture->getFile();
                //give a random name to the file which contains the picture
                $fileName = md5(uniqid()).'.'.$pictureFile->guessExtension();
                //save it into public/uploads/tricks
                $pictureFile->move(
                    $this->getParameter('pictures_directory').'/tricks',
                    $fileName
                );
                $picture->setFileName($fileName);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($picture);
            $manager->flush();

            $this->addFlash(
                'success',
                "New picture recorded!"
            );

            return $this->redirectToRoute('edit_trick',[
                'id'=> $picture->getTricks()->getId(),
                'trick'=>$request->get('tricks')
            ]);
        }

        return $this->render('media/editMedia.html.twig',[
            'form'=> $form->createView(),
            'picture'=> $picture,
            'trick'=>$request->get('tricks')
        ]);

    }

}
