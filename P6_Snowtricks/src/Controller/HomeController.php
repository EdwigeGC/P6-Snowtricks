<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Trick;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     * @Route("/")
     */
    public function index(): Response
    {
        $repository= $this-> getDoctrine()->getRepository(Trick::class);
        $tricks= $repository->findAll();
        return $this->render('view/home.html.twig', [
            'controller_name' => 'HomeController',
            'tricks' => $tricks,
        ]);
    }

    
}
