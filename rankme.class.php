<?php

    require "elements.class.php";
    include_once("csgo-rankme.php");

    class Rankme {
        private $mysql, $settings, $shortcode;

        public function __construct($mysql, $settings) {
            $this -> mysql = $mysql;
            $this -> settings = $settings;
            $this -> shortcode = 'rankme_score_'.$settings['id'];
            $this -> mysql = new wpdb($this -> mysql['login'], $this -> mysql['password'], $this -> mysql['database'], $this -> mysql['host']);
            add_shortcode($this -> shortcode, array($this, 'rankme_scoreboard'));
        }
         
        public function rankme_scoreboard() {
            $query = $this -> mysql -> get_results("SELECT * FROM `rankme` ORDER BY `rankme`.`score` DESC LIMIT 0, 25;");
            $place = 0;
            $ellements = [];
            
            wp_enqueue_script('rankme_ajax', plugins_url('js/rankmeAjax.js', __FILE__), array('jquery'));
            wp_localize_script('rankme_ajax', 'rankmeAjaxPhp', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'id' => $this -> settings['id']
            ));
            
            echo '<table id="rankme_table"><thead><tr>';

            foreach ($this -> settings['scoreboard'] as $key => $value) {
                if ($value) {
                    $ellement = new RankmeElements($key);
                    echo $ellement -> getHead();
                    array_push($ellements, $ellement);
                }
            }
            echo '</tr></thead><tbody>';

            foreach ($query as $key => $row) {
                echo "<tr>";
                foreach ($ellements as $key => $value) {
                    $ellementName = $value -> getName();
                    if ($ellementName == "place") {
                        echo "<td>".++$place."</td>";
                    } else if ($ellementName == "kd") {
                        echo "<td>".round($row -> kills / $row -> deaths, 2)."</td>";
                    } else if ($ellementName == "button") {
                        // add scoreboard id
                        echo '
                        <td>
                            <form methods="get" action="'. $this -> settings["action"] .'">
                                <input type="text" name="steam" value="'. sanitize_text_field($row -> steam) .'" hidden>
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

            ?>

                <div>
                    Count: <select id="rankme_select">
                    <option value="10">10</option>
                    <option value="25" selected>25</option>
                    <option value="50">50</option>
                    <option value="75">75</option>
                    <option value="100">100</option>
                    <option value="200">200</option>
                    </select>
                    <button id="rankme_prev">Previous</button>
                    <button id="rankme_next">Next</button>
                </div>

            <?php
        }

        public function getJson($start, $count) {
            $result = $this -> mysql -> get_results("SELECT * FROM `rankme` ORDER BY `rankme`.`score` DESC LIMIT $start, $count;");
            $place = $start;
            $resultArr = [];

            foreach ($result as $key1 => $value) {
                $localArr = [];
                foreach ($this -> settings['scoreboard'] as $key3 => $option) {
                    if ($key3 == 'place' && $option) {
                        $localArr['place'] = ++$place;
                    }
                    if ($key3 == 'name' && $option) {
                        $localArr['name'] = $value -> name;
                    }
                    if ($key3 == 'steam' && $option) {
                        $localArr['steam'] = $value -> steam;
                    }
                    if ($key3 == 'score' && $option) {
                        $localArr['score'] = $value -> score;
                    }
                    if ($key3 == 'kills' && $option) {
                        $localArr['kills'] = $value -> kills;
                    }
                    if ($key3 == 'deaths' && $option) {
                        $localArr['deaths'] = $value -> deaths;
                    }
                    if ($key3 == 'headshots' && $option) {
                        $localArr['headshots'] = $value -> headshots;
                    }
                    if ($key3 == 'kd' && $option) {
                        $localArr['kd'] = $value -> kills / $value -> deaths;
                    }
                    if ($key3 == 'button' && $option) {
                        $localArr['button'] = [
                            "action" => $this -> settings['action'],
                            "value" => $value -> steam
                        ];
                    }
                }
                array_push($resultArr, $localArr);
            }
            echo json_encode($resultArr);
        }

        public function getShortcode() {
            return $this -> shortcode;
        }
    }

?>