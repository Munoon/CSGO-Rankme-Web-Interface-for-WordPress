<?php

    class RankmeElements {
        private $name;

        public function __construct($name) {
            $this -> name = $name;
        }

        public function getHead() {
            $WORDS = getWords();
            return "<td>". $WORDS[$this -> name][0] ."</td>";
            // place, kd
        }

        public function getTableDash($row) {
            $WORDS = getWords();
            return "<td>". $row[$WORDS[$this -> name][1]] ."</td>";
        }

        public function getJson($row) {
            $WORDS = getWords();
            return $row[$WORDS[$this -> name][1]];
        }

        public function getName() {
            return $this -> name;
        }
    }

    function getWords() {
        return [
            "name" => ["Name", "name"],
            "steam" => ["Steam", "steam"],
            "score" => ["Score", "score"],
            "kills" => ["Kills", "kills"],
            "deaths" => ["Deaths", "deaths"],
            "headshots" => ["Headshots", "headshots"],
            "place" => ["Place", "place"],
            "kd" => ["K/D", "kd"],
            "button" => ["Button", "button"]
        ];
    }

?>