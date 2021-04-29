<?php

declare(strict_types=1);

namespace App\Dice;

use App\Dice\Dice;
use App\Dice\Dicehand;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;

use function App\Functions\sumDiceValue;

/**
 * Class Yatzy
 */
class Yatzy
{
    public function prepareRound(Request $request): void
    {
        $session = $request->getSession();

        $submit = $request->request->get("submit");
        $round = $request->request->get("round");

        if (isset($submit)) {
            $session->set("round", (int)$round);
        }

        $restdices = $session->get("restdices");

        if (isset($restdices)) {
            $session->set("restdices", null);
        }
    }

    public function showRound(Request $request): array
    {
        $session = $request->getSession();

        $data = [
            "header" => "Yatzy Lite",
            "message" => "Collect as many dice for this round as possible."
        ];

        $round = $session->get("round");
        if (isset($round)) {
            $data["round"] = $round;
        }

        return $data;
    }

    public function firstDiceRoll(Request $request): void
    {
        $session = $request->getSession();

        $diceHand = new Dicehand();
        $diceHand->setNumber(4);
        $diceHand->prepare();
        $diceHand->roll();

        $session->set("lastroll", $diceHand->getLastRoll());

        $round = $session->get("round");
        $lastroll = $session->get("lastroll");

        $session->set("wantedvalues", array_keys($lastroll, $round));

        $saveddices = $session->get("saveddices");
        $wantedvalues = $session->get("wantedvalues");

        foreach ($wantedvalues as $key) {
            $saveddices[] = $lastroll[$key];
            $session->set("saveddices", $saveddices);
        }
    }

    public function anotherDiceRoll(Request $request): void
    {
        $session = $request->getSession();

        $diceHand = new Dicehand();

        $lastroll = $session->get("lastroll");
        $restdices = $session->get("restdices");

        $wantedvalues = $session->get("wantedvalues");
        foreach ($wantedvalues as $key) {
            $restdices[] = $lastroll[$key];
            $session->set("restdices", $restdices);
        }

        $number = 4;

        if (isset($restdices)) {
            $number = 4 - count($restdices);
        }

        $diceHand->setNumber($number);
        $diceHand->prepare();
        $diceHand->roll();

        $session->set("lastroll", $diceHand->getLastRoll());

        $round = $session->get("round");
        $lastroll = $session->get("lastroll");

        $session->set("wantedvalues", array_keys($lastroll, $round));

        $saveddices = $session->get("saveddices");
        $wantedvalues = $session->get("wantedvalues");

        foreach ($wantedvalues as $key) {
            $saveddices[] = $lastroll[$key];
            $session->set("saveddices", $saveddices);
        }
    }

    public function showResults(Request $request): array
    {
        $session = $request->getSession();

        $data = [
            "header" => "Yatzy Lite",
            "message" => "Collect as many dice for this round as possible."
        ];

        $round = $session->get("round");
        if (isset($round)) {
            $data["round"] = $round;
        }

        //Sum of only a certain value
        $saveddices = $session->get("saveddices");
        if (isset($saveddices)) {
            $getWantedValues = array_keys($saveddices, $round);
        }

        $sumValues = 0;

        if (isset($saveddices) && isset($getWantedValues)) {
            foreach ($getWantedValues as $key) {
                $sumValues += $saveddices[$key];
            }
        }

        $data["sumValues"] = $sumValues;

        //Show last roll
        $lastroll = $session->get("lastroll");
        if (isset($lastroll)) {
            $data["lastRoll"] = $lastroll;
        }

        return $data;
    }

    public function showEndResults(Request $request): array
    {
        $session = $request->getSession();

        $data = [
            "header" => "Yatzy Lite",
            "message" => "How did it go?"
        ];

        //Export $_SESSION["savedDices"] array to $data
        $saveddices = $session->get("saveddices");

        $data["savedDices"] = $session->get("saveddices");

        //Sum of only a certain value
        $data["sumOnes"] = 0;
        $data["sumTwos"] = 0;
        $data["sumThrees"] = 0;
        $data["sumFours"] = 0;
        $data["sumFives"] = 0;
        $data["sumSixes"] = 0;

        if (isset($saveddices)) {
            $data["sumOnes"] = sumDiceValue($saveddices, 1);
            $data["sumTwos"] = sumDiceValue($saveddices, 2);
            $data["sumThrees"] = sumDiceValue($saveddices, 3);
            $data["sumFours"] = sumDiceValue($saveddices, 4);
            $data["sumFives"] = sumDiceValue($saveddices, 5);
            $data["sumSixes"] = sumDiceValue($saveddices, 6);
        }

        //Total sum:
        $data["sumDice"] = 0;

        if (isset($saveddices)) {
            $data["sumDice"] = array_sum($saveddices);
        }

        return $data;
    }
}
