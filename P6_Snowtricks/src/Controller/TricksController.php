<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Trick;
use App\Repository\TrickRepository;

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

}
