<?php

declare(strict_types=1);

namespace App\Dice;

use App\Dice\Dice;
use App\Dice\Dicehand;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class GameResults as a controller class
 */
class GameResults
{
    public function showResults(Request $request): array
    {
        $session = $request->getSession();

        $data = [
            "header" => "Let's play 21",
            "message" => "If you get 21 you win! If you get more than 21 you lose."
        ];

        $dicetotal = $session->get("dicetotal");
        if (isset($dicetotal)) {
            $diceSession = $dicetotal;
        }

        $data["sumDice"] = 0;

        if (isset($diceSession)) {
            $data["sumDice"] = $diceSession;
        }

        $data["notice"] = "Keep rolling?";

        if (isset($diceSession) && $diceSession > 21) {
            $data["notice"] = "You lose!";
        } elseif (isset($diceSession) && $diceSession == 21) {
            $data["notice"] = "You win!";
        }

        $data["lastRoll"] = [0];
        $dicehand = $session->get("dicehand");

        if (isset($dicehand)) {
            $data["lastRoll"] = $dicehand;
        }

        return $data;
    }

    public function showFinalResults(Request $request): array
    {
        $session = $request->getSession();

        $data = [
            "header" => "Let's play 21",
            "message" => "Final results!",
        ];

        $playertotal = $session->get("playertotal");
        if (isset($playertotal)) {
            $data["getPlayerTotal"] = $playertotal;
        }

        $bottotal = $session->get("bottotal");
        if (isset($bottotal)) {
            $data["getBotTotal"] = $bottotal;
        }

        $data["getFinalResults"] = "";

        if (isset($playertotal) && $playertotal == 21) {
            $data["getFinalResults"] .= "You win!";
        } elseif (isset($playertotal) && $playertotal > 21) {
            $data["getFinalResults"] .= "You're both losers.";
        } elseif (isset($bottotal) && isset($playertotal) && $bottotal == $playertotal) {
            $data["getFinalResults"] .= "Bot wins!";
        } elseif (isset($bottotal) && isset($playertotal) && $bottotal > $playertotal && $bottotal <= 21) {
            $data["getFinalResults"] .= "Bot wins!";
        } elseif (isset($bottotal) && $bottotal > 21) {
            $data["getFinalResults"] .= "You win!";
        }

        $win = $session->get("win");
        $loss = $session->get("loss");

        if ($data["getFinalResults"] == "You win!") {
            $win += 1;
            $session->set("win", $win);
        } elseif ($data["getFinalResults"] == "Bot wins!") {
            $loss += 1;
            $session->set("loss", $loss);
        }

        $data["getWins"] = 0;

        if (isset($win)) {
            $data["getWins"] = $win;
        }

        $data["getLosses"] = 0;

        if (isset($loss)) {
            $data["getLosses"] = $loss;
        }

        return $data;
    }
}
