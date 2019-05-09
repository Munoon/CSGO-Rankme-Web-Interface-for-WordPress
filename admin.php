<?php

    include "database.php";

    function rankme_add_pages() {
        add_menu_page('Rankme Settings', 'Rankme Settings', 8, __FILE__, 'rankme_toplevel_page');
        add_submenu_page(__FILE__, 'Create Scoreboard', 'Create Scoreboard', 8, 'sub-page', 'rankme_create_scoreboard_page');
    }

    function rankme_toplevel_page() {
        echo "<h1>Rankme Settings</h1>";
        if (isset($_GET['scoreboard'])) {
            editScoreboardPage($_GET['scoreboard']);
        } else if (isset($_POST['search'])) {
            deleteDatabaseFromScoreboard($_POST['search']);
            echo "<h3>Confirm! You have deleted the database with id ". $_POST['search'] ." from the table</h3>";
            mainPage();
        } else {
            mainPage();
        }
    }

    function mainPage() {
        $servers = getServer(null);
        $table = "";

        foreach ($servers as $key => $value) {
            $link =  $_SERVER['REQUEST_URI']. "&scoreboard=" .$value['id'];

            $table .= '<tr>
            <td>'. $value['host'] .'</td>
            <td>'. $value['database'] .'</td>
            <td>[rankme_score_'. $value['id'] .']</td>
            <td>
                <a href="'. $link .'"><button>Edit</button></a>                
            </td>
            <td>
                <form method="post">
                    <input type="hidden" name="search" value="'. $value['id'] .'">
                    <input type="submit" value="Delete">
                </form>
            </td>
            </tr>';
        }
        ?>

            <div>
                <h3>Scoreboards</h3>
                <table class="widefat">
                    <thead>
                        <tr>
                            <td>Host</td>
                            <td>Database</td>
                            <td>Shortcode</td>
                            <td>Edit</td>
                            <td>Delete</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $table; ?>
                    </tbody>
                </table>
            </div>

        <?php
    }

    function rankme_create_scoreboard_page() {
        if (isset($_POST['create'])) {
            $mysql = [
                "host" => $_POST['host'],
                "login" => $_POST['login'],
                "password" => $_POST['password'],
                "database" => $_POST['database']
            ];
            $settings = [
                "action" => $_POST['action'],
                "scoreboard" => [
                    "place" => $_POST['place'] == 'on',
                    "name" => $_POST['name'] == 'on',
                    "steam" => $_POST['steam'] == 'on',
                    "score" => $_POST['score'] == 'on',
                    "kills" => $_POST['kills'] == 'on',
                    "deaths" => $_POST['deaths'] == 'on',
                    "headshots" => $_POST['headshots'] == 'on',
                    "kd" => $_POST['kd'] == 'on',
                    "button" => $_POST['button'] == 'on'
                ]
            ];
            addNewScoreboard($mysql, $settings);
        }

        ?>

            <div>
                <h2>Create Scoreboard</h2>

                <form method="post">
                    <div>
                        <table>
                            <tr>
                                <td>Host:</td>
                                <td><input type="text" name="host"></td>
                            </tr>
                            <tr>
                                <td>Login:</td>
                                <td><input type="text" name="login"></td>
                            </tr>
                            <tr>
                                <td>Password:</td>
                                <td><input type="password" name="password"></td>
                            </tr>
                            <tr>
                                <td>Database:</td>
                                <td><input type="text" name="database"></td>
                            </tr>
                        </table>
                    </div>
                    <div>
                        Action: <input type="text" name="action"><br>
                        Place <input type="checkbox" name="place"><br>
                        Name <input type="checkbox" name="name"><br>
                        Steam <input type="checkbox" name="steam"><br>
                        Score <input type="checkbox" name="score"><br>
                        Kills <input type="checkbox" name="kills"><br>
                        Deaths <input type="checkbox" name="deaths"><br>
                        Headshots <input type="checkbox" name="headshots"><br>
                        K/D <input type="checkbox" name="kd"><br>
                        Button <input type="checkbox" name="button"><br>
                    </div>
                    <input type="submit" value="Create" name="create">
                </form>
            </div>

        <?php
    }

    function editScoreboardPage($scoreboardID) {
        wp_enqueue_script('rankme_set_checked', plugins_url('/js/setChecked.js', __FILE__));
        if (isset($_POST['update'])) {
            $update = [
                "id" => $scoreboardID,
                "host" => $_POST['host'],
                "login" => $_POST['login'],
                "password" => $_POST['password'],
                "database" => $_POST['database'],
                "action" => $_POST['action'],
                "place" => $_POST['place'] == 'on' ? true : false,
                "name" => $_POST['name'] == 'on' ? true : false,
                "steam" => $_POST['steam'] == 'on' ? true : false,
                "score" => $_POST['score'] == 'on' ? true : false,
                "kills" => $_POST['kills'] == 'on' ? true : false,
                "deaths" => $_POST['deaths'] == 'on' ? true : false,
                "headshots" => $_POST['headshots'] == 'on' ? true : false,
                "kd" => $_POST['kd'] == 'on' ? true : false,
                "button" => $_POST['button'] == 'on' ? true : false
            ];
            updateScoreboard($update);
        }
        $scoreboard = getServer($scoreboardID);
        ?>

            <div>
                <h3>Edit Scoreboard</h3>

                <form method="post">
                    Host: <input type="text" name="host" value="<?=$scoreboard[0]['host']?>"><br>
                    Login: <input type="text" name="login" value="<?=$scoreboard[0]['login']?>"><br>
                    Password: <input type="password" name="password" value="<?=$scoreboard[0]['password']?>"><br>
                    Database: <input type="text" name="database" value="<?=$scoreboard[0]['database']?>"><br>
                    Action: <input type="text" name="action" value="<?=$scoreboard[0]['action']?>"><br>
                    
                    <div id="rankme_checkbox">
                        Place <input type="checkbox" name="place" data-checked="<?=$scoreboard[0]['place']?>"><br>
                        Name <input type="checkbox" name="name" data-checked="<?=$scoreboard[0]['name']?>"><br>
                        Steam <input type="checkbox" name="steam" data-checked="<?=$scoreboard[0]['steam']?>"><br>
                        Score <input type="checkbox" name="score" data-checked="<?=$scoreboard[0]['score']?>"><br>
                        Kills <input type="checkbox" name="kills" data-checked="<?=$scoreboard[0]['kills']?>"><br>
                        Deaths <input type="checkbox" name="deaths" data-checked="<?=$scoreboard[0]['deaths']?>"><br>
                        Headshots <input type="checkbox" name="headshots" data-checked="<?=$scoreboard[0]['headshots']?>"><br>
                        K/D <input type="checkbox" name="kd" data-checked="<?=$scoreboard[0]['kd']?>"><br>
                        Button <input type="checkbox" name="button" data-checked="<?=$scoreboard[0]['button']?>"><br>
                    </div>
                    <input type="submit" name="update" value="Update">
                </form>
            </div>

        <?php
    }

?>