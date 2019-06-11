<?php

    include "database.php";

    if (!defined('ABSPATH')) exit;

    function rankme_add_pages() {
        add_menu_page('Rankme Settings', 'Rankme Settings', 8, __FILE__, 'rankme_toplevel_page');
        add_submenu_page(__FILE__, 'Add Scoreboard', 'Add Scoreboard', 8, 'sub-page', 'rankme_add_scoreboard_page');
        add_submenu_page(__FILE__, 'Add Ptofile', 'Add Profile', 8, 'sub-page2', 'rankme_add_profile_page');
    }

    function rankme_toplevel_page() {
        echo "<h1>Rankme Settings</h1>";
        if (isset($_GET['scoreboard'])) {
            rankme_editScoreboardPage(sanitize_text_field($_GET['scoreboard']));
        } else if (isset($_GET['profile'])) {
            rankme_editProfilePage(sanitize_text_field($_GET['profile']));
        } else if (isset($_POST['searchDelete'])) {
            $message = sanitize_text_field($_POST['searchDelete']);
            rankme_deleteDatabaseFromScoreboard($message);
            echo "<h3>Confirm! You have deleted the database with id $message from the scoreboards</h3>";
            rankme_main_page();
        } else if (isset($_POST['profileDelete'])) {
            $message = sanitize_text_field($_POST['profileDelete']);
            rankme_deleteDatabaseFromProfiles($message);
            echo "<h3>Confirm! You have deleted the database with id $message from the profiles</h3>";
            rankme_main_page();
        } else {
            rankme_main_page();
        }
    }

    function rankme_main_page() {
        $servers = rankme_getServer(null);
        $profiles = rankme_getProfiles(null);
        $scoreboardTable = "";
        $profileTable = "";

        foreach ($servers as $key => $value) {
            $link =  sanitize_text_field($_SERVER['REQUEST_URI']). "&scoreboard=" .sanitize_text_field($value['id']);

            $scoreboardTable .= '<tr>
            <td>'. $value['host'] .'</td>
            <td>'. $value['database'] .'</td>
            <td>[rankme_score_'. sanitize_html_class($value['id'] ).']</td>
            <td>
                <a href="'. $link .'"><button>Edit</button></a>                
            </td>
            <td>
                <form method="post">
                    <input type="hidden" name="searchDelete" value="'. sanitize_text_field($value['id']) .'">
                    <input type="submit" value="Delete">
                </form>
            </td>
            </tr>';
        }

        foreach ($profiles as $key => $value) {
            $link =  $_SERVER['REQUEST_URI']. "&profile=" .$value['id'];

            $profileTable .= '<tr>
            <td>'. $value['name'] .'</td>
            <td>'. $value['host'] .'</td>
            <td>'. $value['database'] .'</td>
            <td>
                <a href="'. $link .'"><button>Edit</button></a>                
            </td>
            <td>
                <form method="post">
                    <input type="hidden" name="profileDelete" value="'. $value['id'] .'">
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
                        <?php echo $scoreboardTable; ?>
                    </tbody>
                </table>
            </div>

            <div>
                <h3>Players Profile</h3>
                <table class="widefat">
                    <thead>
                        <tr>
                            <td>Name</td>
                            <td>Host</td>
                            <td>Database</td>
                            <td>Edit</td>
                            <td>Delete</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $profileTable; ?>
                    </tbody>
                </table>
                <p>To add Players Profile and Search page insert on your page shortcode: <b>[rankme_search]</b></p>
            </div>

        <?php
    }

    function rankme_add_scoreboard_page() {
        wp_enqueue_script('rankme_show_password', plugins_url('/js/showPassword.js', __FILE__));
        if (isset($_POST['create'])) {
            $mysql = [
                "host" => sanitize_text_field($_POST['host']),
                "login" => sanitize_text_field($_POST['login']),
                "database" => sanitize_text_field($_POST['database']),
                "password" => sanitize_text_field($_POST['password'])
            ];
            $settings = [
                "action" => sanitize_text_field($_POST['action']),
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

            if ($message = rankme_checkInfoForNull(array_slice($mysql, 0, 3))) {
                echo "<h2>$message</h2>";
            } else if (!rankme_checkConnection($mysql)) {
                echo "<h2>Error! Can not connect to the database</h2>";
            } else {
                rankme_addNewScoreboard($mysql, $settings);
                echo "<h3>Confirm! You created new scoreboard!</h3>";
            }
        }

        ?>

            <div>
                <h2>Add Scoreboard</h2>

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
                                <td>
                                    <input type="password" name="password" id="rankme_password_field">
                                    Show password <input type="checkbox" id="rankme_password">
                                </td>
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

    function rankme_add_profile_page() {
        wp_enqueue_script('rankme_show_password', plugins_url('/js/showPassword.js', __FILE__));
        if (isset($_POST['addProfile'])) {
            $settings = [
                "host" => sanitize_text_field($_POST['host']),
                "login" => sanitize_text_field($_POST['login']),
                "database" => sanitize_text_field($_POST['database']),
                "password" => sanitize_text_field($_POST['password']),
                "name" => sanitize_text_field($_POST['name']),
                sanitize_text_field($_POST['showName']) == 'on',
                sanitize_text_field($_POST['steam']) == 'on',
                sanitize_text_field($_POST['score']) == 'on',
                sanitize_text_field($_POST['kills']) == 'on',
                sanitize_text_field($_POST['deaths']) == 'on',
                sanitize_text_field($_POST['headshots']) == 'on',
                sanitize_text_field($_POST['kd']) == 'on',
                sanitize_text_field($_POST['assists']) == 'on',
                sanitize_text_field($_POST['shots']) == 'on',
                sanitize_text_field($_POST['hits']) == 'on',
                sanitize_text_field($_POST['hostage_rescued']) == 'on',
                sanitize_text_field($_POST['damage']) == 'on',
                sanitize_text_field($_POST['mvp']) == 'on',
                sanitize_text_field($_POST['match_isShow']) == 'on',
                sanitize_text_field($_POST['win']) == 'on',
                sanitize_text_field($_POST['draw']) == 'on',
                sanitize_text_field($_POST['lose']) == 'on',
                sanitize_text_field($_POST['models_isShow']) == 'on',
                sanitize_text_field($_POST['head']) == 'on',
                sanitize_text_field($_POST['chest']) == 'on',
                sanitize_text_field($_POST['stomach']) == 'on',
                sanitize_text_field($_POST['left_arm']) == 'on',
                sanitize_text_field($_POST['right_arm']) == 'on',
                sanitize_text_field($_POST['left_leg']) == 'on',
                sanitize_text_field($_POST['riht_leg']) == 'on',
                sanitize_text_field($_POST['guns_isShow']) == 'on',
                sanitize_text_field($_POST['ak47']) == 'on',
                sanitize_text_field($_POST['m4a1']) == 'on',
                sanitize_text_field($_POST['m4a1_silencer']) == 'on',
                sanitize_text_field($_POST['knife']) == 'on',
                sanitize_text_field($_POST['glock']) == 'on',
                sanitize_text_field($_POST['hkp200']) == 'on',
                sanitize_text_field($_POST['usp_silencer']) == 'on',
                sanitize_text_field($_POST['p250']) == 'on',
                sanitize_text_field($_POST['deagle']) == 'on',
                sanitize_text_field($_POST['elite']) == 'on',
                sanitize_text_field($_POST['fiveseven']) == 'on',
                sanitize_text_field($_POST['tec9']) == 'on',
                sanitize_text_field($_POST['cz75a']) == 'on',
                sanitize_text_field($_POST['revolver']) == 'on',
                sanitize_text_field($_POST['nova']) == 'on',
                sanitize_text_field($_POST['xm1014']) == 'on',
                sanitize_text_field($_POST['mag7']) == 'on',
                sanitize_text_field($_POST['sawedoff']) == 'on',
                sanitize_text_field($_POST['bizon']) == 'on',
                sanitize_text_field($_POST['mac10']) == 'on',
                sanitize_text_field($_POST['mp9']) == 'on',
                sanitize_text_field($_POST['mp7']) == 'on',
                sanitize_text_field($_POST['ump45']) == 'on',
                sanitize_text_field($_POST['p90']) == 'on',
                sanitize_text_field($_POST['galilar']) == 'on',
                sanitize_text_field($_POST['scar20']) == 'on',
                sanitize_text_field($_POST['famas']) == 'on',
                sanitize_text_field($_POST['aug']) == 'on',
                sanitize_text_field($_POST['ssg08']) == 'on',
                sanitize_text_field($_POST['sg556']) == 'on',
                sanitize_text_field($_POST['awp']) == 'on',
                sanitize_text_field($_POST['g3sg1']) == 'on',
                sanitize_text_field($_POST['m249']) == 'on',
                sanitize_text_field($_POST['negev']) == 'on',
                sanitize_text_field($_POST['mp5sd']) == 'on'
            ];

            if (sanitize_text_field($_POST['name']) == '') {
                echo "<h2>Error! You need to type name.</h2>";
            } else if (!rankme_checkProfileNameAvailable(sanitize_text_field($_POST['name']))) {
                echo "<h2>Error! That name already exist.</h2>";
            } else if ($message = rankme_checkInfoForNull(array_slice($settings, 0, 3))) {
                echo "<h2>$message</h2>";
            } else if (!rankme_checkConnection(array_slice($settings, 0, 4))) {
                echo "<h2>Error! Can not connect to the database</h2>";
            } else {
                rankme_addNewProfile($settings);
                echo "<h3>Confirm! You created new profile!</h3>";
            }
        }

        ?>

            <div>
                <h2>Add Profile</h2>

                <form method="post">
                    <div>
                        Host <input type="text" name="host"><br>
                        Login <input type="text" name="login"><br>
                        Password <input type="password" name="password" id="rankme_password_field">
                        Show password <input type="checkbox" id="rankme_password"><br>
                        Database <input type="text" name="database"><br>
                        Name <input type="text" name="name"><br>
                    </div>
                    
                    <div>
                        Show Name <input type="checkbox" name="showName"><br>
                        Steam <input type="checkbox" name="steam"><br>
                        Score <input type="checkbox" name="score"><br>
                        Kills <input type="checkbox" name="kills"><br>
                        Deaths <input type="checkbox" name="deaths"><br>
                        Headshots <input type="checkbox" name="headshots"><br>
                        K/D <input type="checkbox" name="kd"><br>
                        Assists <input type="checkbox" name="assists"><br>
                        Shots <input type="checkbox" name="shots"><br>
                        Hits <input type="checkbox" name="hits"><br>
                        Hostage Rescues <input type="checkbox" name="hostage_rescues"><br>
                        Damage <input type="checkbox" name="damage"><br>
                        MVP <input type="checkbox" name="mvp"><br>

                        <div>
                            Show Match <input type="checkbox" name="match_isShow"><br>
                            Win <input type="checkbox" name="win"><br>
                            Draw <input type="checkbox" name="draw"><br>
                            Lose <input type="checkbox" name="lose"><br>
                        </div>

                        <div>
                            Show Models <input type="checkbox" name="models_isShow"><br>
                            Head <input type="checkbox" name="head"><br>
                            Chest <input type="checkbox" name="chest"><br>
                            Stomach <input type="checkbox" name="stomach"><br>
                            Left Arm <input type="checkbox" name="left_arm"><br>
                            Right Arm <input type="checkbox" name="right_arm"><br>
                            Left Leg <input type="checkbox" name="left_leg"><br>
                            Right Leg <input type="checkbox" name="right_leg"><br>
                        </div>

                        <div>
                            Show Guns <input type="checkbox" name="guns_isShow"><br>
                            AK47 <input type="checkbox" name="ak47"><br>
                            M4A1 <input type="checkbox" name="m4a1"><br>
                            M4A1-S <input type="checkbox" name="m4a1_silencer"><br>
                            Knife <input type="checkbox" name="knife"><br>
                            Glock <input type="checkbox" name="glock"><br>
                            P200 <input type="checkbox" name="hkp200"><br>
                            USP-S <input type="checkbox" name="usp_silencer"><br>
                            P250 <input type="checkbox" name="p250"><br>
                            Deagle <input type="checkbox" name="deagle"><br>
                            Elite <input type="checkbox" name="Elite"><br>
                            Five-Seven <input type="checkbox" name="fiveseven"><br>
                            Tec-9 <input type="checkbox" name="tec9"><br>
                            CZ-74 <input type="checkbox" name="cz75a"><br>
                            Revolver <input type="checkbox" name="revolver"><br>
                            Nova <input type="checkbox" name="nova"><br>
                            XM1014 <input type="checkbox" name="xm1014"><br>
                            Mag-7 <input type="checkbox" name="mag7"><br>
                            Sawedoff <input type="checkbox" name="sawedoff"><br>
                            Bizon <input type="checkbox" name="bizon"><br>
                            Mac-10 <input type="checkbox" name="mac10"><br>
                            MP9 <input type="checkbox" name="mp9"><br>
                            MP7 <input type="checkbox" name="mp7"><br>
                            UMP-45 <input type="checkbox" name="ump45"><br>
                            P90 <input type="checkbox" name="p90"><br>
                            Galilar <input type="checkbox" name="galilar"><br>
                            SCAR20 <input type="checkbox" name="scar20"><br>
                            Famas <input type="checkbox" name="famas"><br>
                            AUG <input type="checkbox" name="aug"><br>
                            SSG08 <input type="checkbox" name="ssg08"><br>
                            SG556 <input type="checkbox" name="sg556"><br>
                            AWP <input type="checkbox" name="awp"><br>
                            G3SG1 <input type="checkbox" name="g3sg1"><br>
                            M249 <input type="checkbox" name="m249"><br>
                            Negev <input type="checkbox" name="negev"><br>
                            MP5-SD <input type="checkbox" name="mp5sd"><br>
                        </div>
                    </div>

                    <input type="submit" name="addProfile" value="Add Profile">
                </form>
            </div>

        <?php
    }

    function rankme_editScoreboardPage($scoreboardID) {
        wp_enqueue_script('rankme_set_checked', plugins_url('/js/setChecked.js', __FILE__));
        wp_enqueue_script('rankme_show_password', plugins_url('/js/showPassword.js', __FILE__));
        if (isset($_POST['update'])) {
            $update = [
                "id" => sanitize_text_field($scoreboardID),
                "host" => sanitize_text_field($_POST['host']),
                "login" => sanitize_text_field($_POST['login']),
                "database" => sanitize_text_field($_POST['database']),
                "password" => sanitize_text_field($_POST['password']),
                "action" => sanitize_text_field($_POST['action']),
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

            if ($message = rankme_checkInfoForNull(array_slice($update, 0, 4))) {
                echo "<h2>$message</h2>";
            } else if (!rankme_checkConnection(array_slice($update, 1, 4))) {
                echo "<h2>Error! Can not connect to the database</h2>";
            } else {
                rankme_updateScoreboard($update);
                echo "<h3>Confirm! You updated scoreboard!</h3>";
            }
        }
        $scoreboard = rankme_getServer($scoreboardID);
        ?>

            <div>
                <h3>Edit Scoreboard</h3>

                <form method="post">
                    Host: <input type="text" name="host" value="<?=$scoreboard[0]['host']?>"><br>
                    Login: <input type="text" name="login" value="<?=$scoreboard[0]['login']?>"><br>
                    Password: <input type="password" name="password" id="rankme_password_field" value="<?=$scoreboard[0]['password']?>">
                    Show password <input type="checkbox" id="rankme_password"><br>
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
    
    function rankme_editProfilePage($profileID) {
        wp_enqueue_script('rankme_set_checked', plugins_url('/js/setChecked.js', __FILE__));
        wp_enqueue_script('rankme_show_password', plugins_url('/js/showPassword.js', __FILE__));
        $profiles = rankme_getProfiles($profileID)[0];
        if (isset($_POST['updateProfile'])) {
            $settings = [
                "id" => sanitize_text_field($profileID),
                "host" => sanitize_text_field($_POST['host']),
                "login" => sanitize_text_field($_POST['login']),
                "database" => sanitize_text_field($_POST['database']),
                "password" => sanitize_text_field($_POST['password']),
                "name" => sanitize_text_field($_POST['name']),
                "showName" => $_POST['showName'] == 'on' ? '1' : '0',
                "steam" => $_POST['steam'] == 'on' ? '1' : '0',
                "score" => $_POST['score'] == 'on' ? '1' : '0',
                "kills" => $_POST['kills'] == 'on' ? '1' : '0',
                "deaths" => $_POST['deaths'] == 'on' ? '1' : '0',
                "headshotd" => $_POST['headshots'] == 'on' ? '1' : '0',
                "kd" => $_POST['kd'] == 'on' ? '1' : '0',
                "assists" => $_POST['assists'] == 'on' ? '1' : '0',
                "shots" => $_POST['shots'] == 'on' ? '1' : '0',
                "hits" => $_POST['hits'] == 'on' ? '1' : '0',
                "hostage_rescued" => $_POST['hostage_rescued'] == 'on' ? '1' : '0',
                "damage" => $_POST['damage'] == 'on' ? '1' : '0',
                "mvp" => $_POST['mvp'] == 'on' ? '1' : '0',
                "match_isShow" => $_POST['match_isShow'] == 'on' ? '1' : '0',
                "win" => $_POST['win'] == 'on' ? '1' : '0',
                "draw" => $_POST['draw'] == 'on' ? '1' : '0',
                "lose" => $_POST['lose'] == 'on' ? '1' : '0',
                "model_isShow" => $_POST['models_isShow'] == 'on' ? '1' : '0',
                "head" => $_POST['head'] == 'on' ? '1' : '0',
                "chest" => $_POST['chest'] == 'on' ? '1' : '0',
                "stomach" => $_POST['stomach'] == 'on' ? '1' : '0',
                "left_arm" => $_POST['left_arm'] == 'on' ? '1' : '0',
                "right_arm" => $_POST['right_arm'] == 'on' ? '1' : '0',
                "left_leg" => $_POST['left_leg'] == 'on' ? '1' : '0',
                "right_leg" => $_POST['right_leg'] == 'on' ? '1' : '0',
                "guns_isShow" => $_POST['guns_isShow'] == 'on' ? '1' : '0',
                "ak47" => $_POST['ak47'] == 'on' ? '1' : '0',
                "m4a1" => $_POST['m4a1'] == 'on' ? '1' : '0',
                "m4a1_silencer" => $_POST['m4a1_silencer'] == 'on' ? '1' : '0',
                "knife" => $_POST['knife'] == 'on' ? '1' : '0',
                "glock" => $_POST['glock'] == 'on' ? '1' : '0',
                "hkp200" => $_POST['hkp200'] == 'on' ? '1' : '0',
                "usp_silencer" => $_POST['usp_silencer'] == 'on' ? '1' : '0',
                "p250" => $_POST['p250'] == 'on' ? '1' : '0',
                "deagle" => $_POST['deagle'] == 'on' ? '1' : '0',
                "elite" => $_POST['elite'] == 'on' ? '1' : '0',
                "fiveseven" => $_POST['fiveseven'] == 'on' ? '1' : '0',
                "tec9" => $_POST['tec9'] == 'on' ? '1' : '0',
                "cz75a" => $_POST['cz75a'] == 'on' ? '1' : '0',
                "revolver" => $_POST['revolver'] == 'on' ? '1' : '0',
                "nova" => $_POST['nova'] == 'on' ? '1' : '0',
                "xm1014" => $_POST['xm1014'] == 'on' ? '1' : '0',
                "mag7" => $_POST['mag7'] == 'on' ? '1' : '0',
                "sawedoff" => $_POST['sawedoff'] == 'on' ? '1' : '0',
                "bizon" => $_POST['bizon'] == 'on' ? '1' : '0',
                "mac10" => $_POST['mac10'] == 'on' ? '1' : '0',
                "mp9" => $_POST['mp9'] == 'on' ? '1' : '0',
                "mp7" => $_POST['mp7'] == 'on' ? '1' : '0',
                "ump45" => $_POST['ump45'] == 'on' ? '1' : '0',
                "p90" => $_POST['p90'] == 'on' ? '1' : '0',
                "galilar" => $_POST['galilar'] == 'on' ? '1' : '0',
                "scar20" => $_POST['scar20'] == 'on' ? '1' : '0',
                "famas" => $_POST['famas'] == 'on' ? '1' : '0',
                "aug" => $_POST['aug'] == 'on' ? '1' : '0',
                "ssg08" => $_POST['ssg08'] == 'on' ? '1' : '0',
                "sg556" => $_POST['sg556'] == 'on' ? '1' : '0',
                "awp" => $_POST['awp'] == 'on' ? '1' : '0',
                "g3sg1" => $_POST['g3sg1'] == 'on' ? '1' : '0',
                "m249" => $_POST['m249'] == 'on' ? '1' : '0',
                "negev" => $_POST['negev'] == 'on' ? '1' : '0',
                "mp5sd" => $_POST['mp5sd'] == 'on' ? '1' : '0'
            ];
            if (sanitize_text_field($_POST['name']) == '') {
                echo "<h2>Error! You need to type name.</h2>";
            } else if ($profiles['name'] != $_POST['name'] && !rankme_checkProfileNameAvailable(sanitize_text_field($_POST['name']))) {
                echo "<h2>Error! That name already exist.</h2>";
            } else if ($message = rankme_checkInfoForNull(array_slice($settings, 1, 3))) {
                echo "<h2>$message</h2>";
            } else if (!rankme_checkConnection(array_slice($settings, 1, 4))) {
                echo "<h2>Error! Can not connect to the database</h2>";
            } else {
                rankme_updateProfile($settings);
                echo "<h3>Confirm! You updated profile!</h3>";
            }
        }
        ?>

            <div>
                <h2>Edit Profile</h2>

                <form method="post">
                    <div>
                        Host <input type="text" name="host" value="<?=$profiles['host']?>"><br>
                        Login <input type="text" name="login" value="<?=$profiles['login']?>"><br>
                        Password <input type="password" name="password" id="rankme_password_field" value="<?=$profiles['password']?>">
                        Show password <input type="checkbox" id="rankme_password"><br>
                        Database <input type="text" name="database" value="<?=$profiles['database']?>"><br>
                        Name <input type="text" name="name" value="<?=$profiles['name']?>"><br>
                    </div>
                    
                    <div id="rankme_checkbox">
                        Show Name <input type="checkbox" name="showName" data-checked="<?=$profiles['showName']?>"><br>
                        Steam <input type="checkbox" name="steam" data-checked="<?=$profiles['steam']?>"><br>
                        Score <input type="checkbox" name="score" data-checked="<?=$profiles['score']?>"><br>
                        Kills <input type="checkbox" name="kills" data-checked="<?=$profiles['kills']?>"><br>
                        Deaths <input type="checkbox" name="deaths" data-checked="<?=$profiles['deaths']?>"><br>
                        Headshots <input type="checkbox" name="headshots" data-checked="<?=$profiles['headshots']?>"><br>
                        K/D <input type="checkbox" name="kd" data-checked="<?=$profiles['kd']?>"><br>
                        Assists <input type="checkbox" name="assists" data-checked="<?=$profiles['assists']?>"><br>
                        Shots <input type="checkbox" name="shots" data-checked="<?=$profiles['shots']?>"><br>
                        Hits <input type="checkbox" name="hits" data-checked="<?=$profiles['hits']?>"><br>
                        Hostage Rescues <input type="checkbox" name="hostage_rescued" data-checked="<?=$profiles['hostage_rescued']?>"><br>
                        Damage <input type="checkbox" name="damage" data-checked="<?=$profiles['damage']?>"><br>
                        MVP <input type="checkbox" name="mvp" data-checked="<?=$profiles['mvp']?>"><br>

                        <div>
                            Show Match <input type="checkbox" name="match_isShow" data-checked="<?=$profiles['match_isShow']?>"><br>
                            Win <input type="checkbox" name="win" data-checked="<?=$profiles['win']?>"><br>
                            Draw <input type="checkbox" name="draw" data-checked="<?=$profiles['draw']?>"><br>
                            Lose <input type="checkbox" name="lose" data-checked="<?=$profiles['lose']?>"><br>
                        </div>

                        <div>
                            Show Models <input type="checkbox" name="models_isShow" data-checked="<?=$profiles['models_isShow']?>"><br>
                            Head <input type="checkbox" name="head" data-checked="<?=$profiles['head']?>"><br>
                            Chest <input type="checkbox" name="chest" data-checked="<?=$profiles['chest']?>"><br>
                            Stomach <input type="checkbox" name="stomach" data-checked="<?=$profiles['stomach']?>"><br>
                            Left Arm <input type="checkbox" name="left_arm" data-checked="<?=$profiles['left_arm']?>"><br>
                            Right Arm <input type="checkbox" name="right_arm" data-checked="<?=$profiles['right_arm']?>"><br>
                            Left Leg <input type="checkbox" name="left_leg" data-checked="<?=$profiles['left_leg']?>"><br>
                            Right Leg <input type="checkbox" name="right_leg" data-checked="<?=$profiles['right_leg']?>"><br>
                        </div>

                        <div>
                            Show Guns <input type="checkbox" name="guns_isShow" data-checked="<?=$profiles['guns_isShow']?>"><br>
                            AK47 <input type="checkbox" name="ak47" data-checked="<?=$profiles['ak47']?>"><br>
                            M4A1 <input type="checkbox" name="m4a1" data-checked="<?=$profiles['m4a1']?>"><br>
                            M4A1-S <input type="checkbox" name="m4a1_silencer" data-checked="<?=$profiles['m4a1_silencer']?>"><br>
                            Knife <input type="checkbox" name="knife" data-checked="<?=$profiles['knife']?>"><br>
                            Glock <input type="checkbox" name="glock" data-checked="<?=$profiles['glock']?>"><br>
                            P200 <input type="checkbox" name="hkp200" data-checked="<?=$profiles['hkp200']?>"><br>
                            USP-S <input type="checkbox" name="usp_silencer" data-checked="<?=$profiles['usp_silencer']?>"><br>
                            P250 <input type="checkbox" name="p250" data-checked="<?=$profiles['p250']?>"><br>
                            Deagle <input type="checkbox" name="deagle" data-checked="<?=$profiles['deagle']?>"><br>
                            Elite <input type="checkbox" name="elite" data-checked="<?=$profiles['elite']?>"><br>
                            Five-Seven <input type="checkbox" name="fiveseven" data-checked="<?=$profiles['fiveseven']?>"><br>
                            Tec-9 <input type="checkbox" name="tec9" data-checked="<?=$profiles['tec9']?>"><br>
                            CZ-74 <input type="checkbox" name="cz75a" data-checked="<?=$profiles['cz75a']?>"><br>
                            Revolver <input type="checkbox" name="revolver" data-checked="<?=$profiles['revolver']?>"><br>
                            Nova <input type="checkbox" name="nova" data-checked="<?=$profiles['nova']?>"><br>
                            XM1014 <input type="checkbox" name="xm1014" data-checked="<?=$profiles['xm1014']?>"><br>
                            Mag-7 <input type="checkbox" name="mag7" data-checked="<?=$profiles['mag7']?>"><br>
                            Sawedoff <input type="checkbox" name="sawedoff" data-checked="<?=$profiles['sawedoff']?>"><br>
                            Bizon <input type="checkbox" name="bizon" data-checked="<?=$profiles['bizon']?>"><br>
                            Mac-10 <input type="checkbox" name="mac10" data-checked="<?=$profiles['mac10']?>"><br>
                            MP9 <input type="checkbox" name="mp9" data-checked="<?=$profiles['mp9']?>"><br>
                            MP7 <input type="checkbox" name="mp7" data-checked="<?=$profiles['mp7']?>"><br>
                            UMP-45 <input type="checkbox" name="ump45" data-checked="<?=$profiles['ump45']?>"><br>
                            P90 <input type="checkbox" name="p90" data-checked="<?=$profiles['p90']?>"><br>
                            Galilar <input type="checkbox" name="galilar" data-checked="<?=$profiles['galilar']?>"><br>
                            SCAR20 <input type="checkbox" name="scar20" data-checked="<?=$profiles['scar20']?>"><br>
                            Famas <input type="checkbox" name="famas" data-checked="<?=$profiles['famas']?>"><br>
                            AUG <input type="checkbox" name="aug" data-checked="<?=$profiles['aug']?>"><br>
                            SSG08 <input type="checkbox" name="ssg08" data-checked="<?=$profiles['ssg08']?>"><br>
                            SG556 <input type="checkbox" name="sg556" data-checked="<?=$profiles['sg556']?>"><br>
                            AWP <input type="checkbox" name="awp" data-checked="<?=$profiles['awp']?>"><br>
                            G3SG1 <input type="checkbox" name="g3sg1" data-checked="<?=$profiles['g3sg1']?>"><br>
                            M249 <input type="checkbox" name="m249" data-checked="<?=$profiles['m249']?>"><br>
                            Negev <input type="checkbox" name="negev" data-checked="<?=$profiles['negev']?>"><br>
                            MP5-SD <input type="checkbox" name="mp5sd" data-checked="<?=$profiles['mp5sd']?>"><br>
                        </div>
                    </div>

                    <input type="submit" name="updateProfile" value="Edit Profile">
                </form>
            </div>

        <?php
    }

    function rankme_checkInfoForNull($info) {
        foreach ($info as $key => $value) {
            if ($value == "") {
                $key = sanitize_text_field($key);
                return "Error! You need to type value in $key field!";
            }
        }
    }

?>