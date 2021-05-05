<?php

namespace App\Controller;

use App\Entity\Highscore;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HighscoreController extends AbstractController
{
    /**
     * @Route("/highscore", name="highscore")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $data = array();

        $query = $entityManager->createQuery('SELECT h FROM App\Entity\Highscore h ORDER BY h.score DESC');

        $data["highscore"] = $query->getResult();

        return $this->render('highscore/index.html.twig', $data);
    }

    /**
     * @Route("/highscore/process", name="highscore_process")
     */
    public function processHighscore(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $score = $request->request->get("score");
        $date = $request->request->get("date");
        $name = $request->request->get("name");

        $highscore = new Highscore();
        $highscore->setScore($score);
        $highscore->setDate($date);
        $highscore->setName($name);

        $entityManager->persist($highscore);

        $entityManager->flush();

        return $this->redirectToRoute('highscore');
    }
}
