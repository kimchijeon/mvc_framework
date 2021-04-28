<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
    */
    public function index(): Response
    {
        $session = new Session();
        $session->start();

        $data = [
            "header" => "Index Page",
            "message" => "Hello, this is the index page, rendered as a layout.",
        ];

        return $this->render('index.html.twig', $data);
    }
}
