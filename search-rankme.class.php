<?php

    class RankmeSearch {
        private $mysql;

        public function __construct($mysql) {
            $this -> mysql = $mysql;
            add_shortcode('rankme_search', array($this, 'rankme_search'));
        }

        public function rankme_search() {
            if (isset($_GET['steam'])) {
                wp_enqueue_script('rankme_switch_panel', plugins_url('/js/switchpanel.js', __FILE__));
                ?>

                    <div id="rankme_search">
                    <div id="rankme_search__panel"></div>
                
                <?php 
                foreach ($this -> mysql as $key => $value) {
                    echo '<div id="'. $value['name'] .'" hidden>';

                    $mysql = new mysqli($value['host'], $value['login'], $value['password'], $value['database']);
                    $query = $mysql -> query("SELECT * FROM `rankme` WHERE steam = '$_GET[steam]';");
                    $words = getSettingsWords();
                    $settings = $value['settings'];
                    
                    if ($row = mysqli_fetch_assoc($query)) {
                        // Show global statistics
                        echo "<table>";
                        foreach ($settings as $key => $value) {

                            if ($value && $key != "match" && $key != "models" && $key != "guns" && $key != "kd") {
                                echo "
                                <tr>
                                    <td>". $words[$key][1] ."</td>
                                    <td>". $row[$words[$key][0]] ."</td>
                                </tr>
                                ";
                            } else if ($key == "kd") {
                                echo "
                                <tr>
                                    <td>". $words[$key][1] ."</td>
                                    <td>". round($row['kills'] / $row['deaths'], 2) ."</td>
                                </tr>
                                ";
                            }

                        }
                        echo "</table>";

                        // Show match statistics
                        if ($settings['match']['isShow']) {
                            echo '<table><tbody>';
                            
                            foreach ($settings['match'] as $matchType => $value) {
                                if ($matchType != 'isShow' && $value) {
                                    echo "
                                    <tr>
                                        <td>". $words['match'][$matchType][1] ."</td>
                                        <td>". $row[$words['match'][$matchType][0]] ."</td>
                                    </tr>
                                    ";
                                }
                            }

                            echo "</tbody></table>";
                        }

                        // Show models statistics
                        if ($settings['models']['isShow']) {
                            echo '<table><tbody>';
                            
                            foreach ($settings['models'] as $modelType => $value) {
                                if ($modelType != 'isShow' && $value) {
                                    echo "
                                    <tr>
                                        <td>". $words['models'][$modelType][1] ."</td>
                                        <td>". $row[$words['models'][$modelType][0]] ."</td>
                                    </tr>
                                    ";
                                }
                            }

                            echo "</tbody></table>";
                        }

                        // Show guns statistics
                        if ($settings['guns']['isShow']) {
                            echo "<table><tbody>";

                            foreach ($settings['guns'] as $gun => $value) {
                                if ($gun != "isShow" && $value) {
                                    echo "
                                    <tr>
                                        <td>". $words['guns'][$gun][1] ."</td>
                                        <td>". $row[$words['guns'][$gun][0]] ."</td>
                                    </tr>                                    
                                    ";
                                }
                            }

                            echo "</tbody></table>";
                        }
                    }

                    echo "</div>";
                }
                echo "</div>";
            } else {
                ?>
                <div>
                    <h1>Search</h1>
                    <form method="get">
                        <input type="text" name="steam" placeholder="Steam ID">
                        <input type="submit" value="Search">   
                    </form>
                </div>
                <?php
            }
        }
    }

    function getSettingsWords() {
        return [
            "name" => ["name", "Name"],
            "steam" => ["steam", "SteamID"],
            "score" => ["score", "Score"],
            "kills" => ["kills", "Kills"],
            "deaths" => ["deaths", "Deaths"],
            "headshots" => ["headshots", "Headshots"],
            "kd" => ["kd", "kd"],
            "assists" => ["assists", "Assists"],
            "shots" => ["shots", "Shots"],
            "hits" => ["hits", "Hits"],
            "hostages_rescued" => ["hostages_rescued", "Hostage Rescued"],
            "damage" => ["damage", "Damage"],
            "mvp" => ["mvp", "MPV"],
            "c4_planted" => ["c4_planted", "C4 Planted"],
            "c4_exploded" => ["c4_exploded", "C4 Exploded"], 
            "c4_defused" => ["c4_defused", "C4 Defused"],
            "first_blood" => ["first_blood", "First Blood"],
            "no_scope" => ["no_scope", "No Scope"],
            "no_scope_dis" => ["no_scope_dis", "No Scope Dis"],
            "match" => [
                "win" => ["match_win", "Win Matches"],
                "draw" => ["match_draw", "Draw Matches"],
                "lose" => ["match_lose", "Lose Matches"],
                "rounds_tr" => ["rounds_tr", "Round TR"],
                "rounds_ct" => ["rounds_ct", "Round CT"],
                "ct_win" => ["ct_win", "CT Win"],
                "tr_win" => ["tr_win", "TR Win"]
            ],
            "models" => [
                "head" => ["head", "Head"],
                "chest" => ["chest", "Chest"],
                "stomach" => ["stomach", "Stomach"],
                "left_arm" => ["left_arm", "Left Arm"],
                "right_arm" => ["right_arm", "Right Arm"],
                "left_leg" => ["left_leg", "Left Leg"],
                "right_leg" => ["right_leg", "Right Leg"]
            ],
            "guns" => [
                "knife" => ["knife", "Knife"],
                "glock" => ["glock", "Glock"],
                "hkp200" => ["hkp2000", "P200"],
                "usp_silencer" => ["usp_silencer", "USP-S"],
                "p250" => ["p250", "P250"],
                "deagle" => ["deagle", "Deagle"],
                "elite" => ["elite", "Elite"],
                "fiveseven" => ["fiveseven", "Five-Seven"],
                "tec9" => ["tec9", "Tec-9"],
                "cz75a" => ["cz75a", "CZ-75"],
                "revolver" => ["revolver", "Revolver"],
                "nova" => ["nova", "Nova"],
                "xm1014" => ["xm1014", "XM1014"],
                "mag7" => ["mag7", "MAG-7"],
                "sawedoff" => ["sawedoff", "Sawedoff"],
                "bizon" => ["bizon", "Bizon"],
                "mac10" => ["mac10", "Mac10"],
                "mp9" => ["mp9", "MP9"],
                "mp7" => ["mp7", "MP7"],
                "ump45" => ["ump45", "UMP-45"],
                "p90" => ["p90", "P90"],
                "galilar" => ["galilar", "Galilar"],
                "ak47" => ["ak47", "AK-47"],
                "scar20" => ["scar20", "SCAR-20"],
                "famas" => ["famas", "Famas"],
                "m4a1" => ["m4a1", "M4A1"],
                "m4a1_silencer" => ["m4a1_silencer", "M4A1-S"],
                "aug" => ["aug", "AUG"],
                "ssg08" => ["ssg08", "SSG-08"],
                "sg556" => ["sg556", "SG-556"],
                "awp" => ["awp", "AWP"],
                "g3sg1" => ["g3sg1", "G3SG1"],
                "m249" => ["m249", "M249"],
                "negev" => ["negev", "Negev"],
                "mp5sd" => ["mp5sd", "MP5-SD"]
            ]
        ];
    }

?>