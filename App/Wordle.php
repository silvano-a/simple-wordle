<?php

namespace App;

class Wordle
{
    public $wordList = ['Abuse','Adult','Agent','Anger','Apple','Award','Basis','Beach','Birth','Block','Blood','Board','Brain','Bread','Break','Brown','Buyer','Cause','Chain','Chair','Chest','Chief','Child','China','Claim','Class','Clock','Coach','Coast','Court','Cover','Cream','Crime','Cross','Crowd','Crown','Cycle','Dance','Death','Depth','Doubt','Draft','Drama','Dream','Dress','Drink','Drive','Earth','Enemy','Entry','Error','Event','Faith','Fault','Field','Fight','Final','Floor','Focus','Force','Frame','Frank','Front','Fruit','Glass','Grant','Grass','Green','Group','Guide','Heart','Henry','Horse','Hotel','House','Image','Index','Input','Issue','Japan','Jones','Judge','Knife','Laura','Layer','Level','Lewis','Light','Limit','Lunch','Major','March','Match','Metal','Model','Money','Month','Motor','Mouth','Music','Night','Noise','North','Novel','Nurse','Offer','Order','Other','Owner','Panel','Paper','Party','Peace','Peter','Phase','Phone','Piece','Pilot','Pitch','Place','Plane','Plant','Plate','Point','Pound','Power','Press','Price','Pride','Prize','Proof','Queen','Radio','Range','Ratio','Reply','Right','River','Round','Route','Rugby','Scale','Scene','Scope','Score','Sense','Shape','Share','Sheep','Sheet','Shift','Shirt','Shock','Sight','Simon','Skill','Sleep','Smile','Smith','Smoke','Sound','South','Space','Speed','Spite','Sport','Squad','Staff','Stage','Start','State','Steam','Steel','Stock','Stone','Store','Study','Stuff','Style','Sugar','Table','Taste','Terry','Theme','Thing','Title','Total','Touch','Tower','Track','Trade','Train','Trend','Trial','Trust','Truth','Uncle','Union','Unity','Value','Video','Visit','Voice','Waste','Watch','Water','While','White','Whole','Woman','World','Youth'];

    private $solution = null;
    private $turn = 0;

    public function __construct()
    {
        $this->solution = $this->wordList[rand(0, count($this->wordList) -1)];
    }

    public function guessRow($row): Row {
        $pspell = pspell_new("en");

        if(strlen($row) != 5 || pspell_check($pspell, $row) === false) {
            throw new \Exception('Het woord moet 5 karakters lang zijn');
        }


        $row = new Row(str_split($row));

        $answer = $row->validate($this->solution);
        $this->turn++;
        if($answer->isSolution()) {
            die('Je hebt gewonnen. Het woord was '. $this->solution. '. Je hebt er '. $this->turn . ' beurten over gedaan');
        }

        if($this->turn === 5)
        {
            die('Je hebt verloren. Het woord was: ' . $this->solution);
        }

        return $answer;
    }
}

class Row
{
    public const POSITION_NONE = 1;
    public const POSITION_WRONG = 2;
    public const POSITION_CORRECT = 3;

    private $word;
    private $arrayWord;
    private $isSolution;

    public function __construct($word)
    {
        $this->word = implode($word);
        $this->arrayWord = array_map(function($l) {
            return [
                'letter' => $l,
                'position' => null
            ];
        },$word);

        $this->isSolution = false;
    }

    /**
     * @return string
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * @return array
     */
    public function getArrayWord()
    {
        return $this->arrayWord;
    }

    public function validate($solution)
    {
        $splitSolution = str_split($solution);

        $correctCounter = 0;
        foreach($this->getArrayWord() as $key => $letterRow) {


            if($splitSolution[$key] === $letterRow['letter']) {
                $correctCounter ++;
                $this->arrayWord[$key]['position'] = self::POSITION_CORRECT;
            }elseif(in_array($letterRow['letter'], $splitSolution)) {
                $this->arrayWord[$key]['position'] = self::POSITION_WRONG;
            }else {
                $this->arrayWord[$key]['position'] = self::POSITION_NONE;
            }
        }

        if($this->getWord() === $solution) {
            $this->isSolution = true;
        }

        return $this;
    }

    /**
     * @return false
     */
    public function isSolution()
    {
        return $this->isSolution;
    }
}


// how to run.
$game = new Wordle();

$game->guessRow('elope');
$game->guessRow('boost');
$game->guessRow('valid');
$game->guessRow('knoll');
$game->guessRow('skill');
$game->guessRow('whack');