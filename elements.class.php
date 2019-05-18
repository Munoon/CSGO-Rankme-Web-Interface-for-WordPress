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
            $word = $WORDS[$this -> name][1];
            return "<td>". sanitize_text_field($row -> $word) ."</td>";
        }

        public function getJson($row) {
            $WORDS = getWords();
            $word = $WORDS[$this -> name][1];
            return $row -> $word;
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