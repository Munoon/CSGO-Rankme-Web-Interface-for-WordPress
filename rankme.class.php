<?php

    require "elements.class.php";

    class Rankme {
        private $mysql, $settings, $shortcode;

        public function __construct($mysql, $settings) {
            $this -> mysql = $mysql;
            $this -> settings = $settings;
            $this -> shortcode = 'rankme_score_'.$settings['id'];
            add_shortcode($this -> shortcode, array($this, 'rankme_scoreboard'));
        }
         
        public function rankme_scoreboard() {
            $mysql = new mysqli($this -> mysql['host'], $this -> mysql['login'], $this -> mysql['password'], $this -> mysql['database']);
            $query = $mysql -> query("SELECT * FROM `rankme` ORDER BY `rankme`.`score` DESC LIMIT ". $this -> settings['start'] .", ". $this -> settings['end'] .";");
            $place = $this -> settings['start'];
            $ellements = [];
            
            echo "<table><thead><tr>";

            foreach ($this -> settings['scoreboard'] as $key => $value) {
                if ($value) {
                    $ellement = new RankmeElements($key);
                    echo $ellement -> getHead();
                    array_push($ellements, $ellement);
                }
            }
            echo "</tr></thead><tbody>";

            while ($row = mysqli_fetch_assoc($query)) {
                echo "<tr>";
                foreach ($ellements as $key => $value) {
                    $ellementName = $value -> getName();
                    if ($ellementName == "place") {
                        echo "<td>".++$place."</td>";
                    } else if ($ellementName == "kd") {
                        echo "<td>".round($row['kills'] / $row['deaths'], 2)."</td>";
                    } else if ($ellementName == "button") {
                        // add scoreboard id
                        echo '
                        <td>
                            <form methods="get" action="'. $this -> settings['action'] .'">
                                <input type="text" name="steam" value="'. $row['steam'] .'" hidden>
                                <input type="submit" value="Profile">
                            </form>
                        </td>';
                    } else {
                        echo $value -> getTableDash($row);
                    }
                }
                echo "</tr>";
            }

            echo "</tbody></table>";
        }

        public function getShortcode() {
            return $this -> shortcode;
        }
    } 

?>