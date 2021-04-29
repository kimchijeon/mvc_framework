<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Dice\Yatzy;

/**
 * Controller for Yatzy routes.
 */
class YatzyGameController extends AbstractController
{
    /**
     * @Route("/yatzy", name="yatzy")
    */
    public function index(): Response
    {
        $data = [
            "header" => "Yatzy Lite",
            "message" => "Collect Ones, Twos, Threes, Fours and Sixes and score as high a sum as possible.
            If you get a sum of at least 63 you get a 50 point bonus!",
        ];

        return $this->render('yatzy/index.html.twig', $data);
    }

    /**
     * @Route("/yatzy/process", name="yatzy_process")
    */
    public function setRoundProcess(Request $request): Response
    {
        $callable = new Yatzy();
        $callable->prepareRound($request);

        return $this->redirectToRoute('yatzy_game');
    }

    /**
     * @Route("/yatzy/game", name="yatzy_game")
    */
    public function game(Request $request): Response
    {
        $callable = new Yatzy();
        $data = $callable->showRound($request);

        return $this->render('yatzy/game.html.twig', $data);
    }

    /**
     * @Route("/yatzy/roll/1", name="yatzy_roll_1")
    */
    public function firstRoll(Request $request): Response
    {
        $callable = new Yatzy();
        $callable->firstDiceRoll($request);

        return $this->redirectToRoute('yatzy_game_firstroll');
    }

    /**
     * @Route("/yatzy/game/firstroll", name="yatzy_game_firstroll")
    */
    public function gameFirstRoll(Request $request): Response
    {
        $callable = new Yatzy();
        $data = $callable->showResults($request);

        return $this->render('yatzy/firstroll.html.twig', $data);
    }

    /**
     * @Route("/yatzy/roll/2", name="yatzy_roll_2")
    */
    public function secondRoll(Request $request): Response
    {
        $callable = new Yatzy();
        $callable->anotherDiceRoll($request);

        return $this->redirectToRoute('yatzy_game_secondroll');
    }

    /**
     * @Route("/yatzy/game/secondroll", name="yatzy_game_secondroll")
    */
    public function gameSecondRoll(Request $request): Response
    {
        $callable = new Yatzy();
        $data = $callable->showResults($request);

        return $this->render('yatzy/secondroll.html.twig', $data);
    }

    /**
     * @Route("/yatzy/roll/3", name="yatzy_roll_3")
    */
    public function thirdRoll(Request $request): Response
    {
        $callable = new Yatzy();
        $callable->anotherDiceRoll($request);

        return $this->redirectToRoute('yatzy_game_thirdroll');
    }

    /**
     * @Route("/yatzy/game/thirdroll", name="yatzy_game_thirdroll")
    */
    public function gameThirdRoll(Request $request): Response
    {
        $callable = new Yatzy();
        $data = $callable->showResults($request);

        return $this->render('yatzy/thirdroll.html.twig', $data);
    }

    /**
     * @Route("/yatzy/round-end", name="yatzy_roundend")
    */
    public function roundEndResults(Request $request): Response
    {
        $callable = new Yatzy();
        $data = $callable->showEndResults($request);

        return $this->render('yatzy/roundend.html.twig', $data);
    }

    /**
     * @Route("/yatzy/restart", name="yatzy_restart")
    */
    public function restart(Request $request): Response
    {
        $session = $request->getSession();
        $session->clear();

        return $this->redirectToRoute('yatzy');
    }
}
