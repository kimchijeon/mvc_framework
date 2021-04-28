<?php

declare(strict_types=1);

namespace Kimchi\Dice;

use Kimchi\Dice\Dice;
use Kimchi\Dice\Dicehand;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Game as a controller class
 */
class Game
{
    public function setDiceNumber(Request $request): void
    {
        $session = $request->getSession();

        $submit = $request->request->get("submit");
        $number = $request->request->get("number");

        if (isset($submit)) {
            $session->set("number", (int)$number);
        }
    }

    public function playGame(Request $request): void
    {
        $session = $request->getSession();
        $diceHand = new Dicehand();

        $number = $session->get("number");

        if (isset($number)) {
            $diceHand->setNumber($number);
        }

        $diceHand->prepare();
        $diceHand->roll();

        $session->set("dicehand", $diceHand->getLastRoll());
        $dicehand = $session->get("dicehand");
        $dicehandSum = array_sum($dicehand);

        $dicetotal = $session->get("dicetotal");

        if (!isset($dicetotal)) {
            $session->set("dicetotal", $dicehandSum);
        }

        $dicetotal = $dicehandSum + $dicetotal;
        $session->set("dicetotal", $dicetotal);
    }

    public function savePlayerTotal(Request $request): void
    {
        $session = $request->getSession();

        $submit = $request->request->get("submit");
        $playerdice = $request->request->get("playerdice");

        if (isset($submit)) {
            $session->set("playertotal", (int)$playerdice);
        }
    }

    public function prepareBotGame(Request $request): array
    {
        $session = $request->getSession();

        $data = [
            "header" => "Let's play 21",
            "message" => "It's the bot's turn to play. Who will win?",
        ];

        $playertotal = $session->get("playertotal");

        if (isset($playertotal)) {
            $data["getPlayerTotal"] = $playertotal;
        }

        return $data;
    }

    public function botRoll(Request $request): void
    {
        $session = $request->getSession();
        $playertotal = $session->get("playertotal");
        $bottotal = $session->get("bottotal");

        $botHand = new Dice();

        //Bot will roll until it has a higher or equal number to player.
        if (isset($playertotal) && !isset($bottotal)) {
            $bottotal = $botHand->roll();
            $session->set("bottotal", $bottotal);
        }

        while (isset($playertotal) && $bottotal < $playertotal) {
            $botHand->roll();

            $session->set("bothand", $botHand->getLastRoll());
            $bothand = $session->get("bothand");

            $bottotal = $bothand + ($bottotal);
            $session->set("bottotal", $bottotal);
        }
    }

    public function prepareGame(Request $request): void
    {
        $session = $request->getSession();

        $number = $session->get("number");
        if (isset($number)) {
            $session->set("number", null);
        }

        $dicehand = $session->get("dicehand");
        if (isset($dicehand)) {
            $session->set("dicehand", null);
        }

        $dicetotal = $session->get("dicetotal");
        if (isset($dicetotal)) {
            $session->set("dicetotal", null);
        }

        $playertotal = $session->get("playertotal");
        if (isset($playertotal)) {
            $session->set("playertotal", null);
        }

        $bothand = $session->get("bothand");
        if (isset($bothand)) {
            $session->set("bothand", null);
        }

        $bottotal = $session->get("bottotal");
        if (isset($bottotal)) {
            $session->set("bottotal", null);
        }
    }
}
