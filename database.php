<?php

    include_once($_SERVER['DOCUMENT_ROOT'].'/wordpress/wp-config.php');

    global $rankme_scoreboard_db_version;
    $rankme_scoreboard_db_version = 1.0;

    global $rankme_profile_db_version;
    $rankme_profile_db_version = 1.0;

    function rankme_createScoreboardTable() {
        global $wpdb;
        global $rankme_scoreboard_db_version;

        $table_name = $wpdb -> prefix . "rankme_scoreboard";
        if ($wpdb -> get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $sql = "CREATE TABLE ". $table_name ." ( 
                id INT NOT NULL AUTO_INCREMENT, 
                host TEXT NOT NULL, 
                login TEXT NOT NULL, 
                password TEXT NOT NULL, 
                db TEXT NOT NULL, 
                action TEXT NOT NULL, 
                place BOOLEAN NOT NULL, 
                name BOOLEAN NOT NULL, 
                steam BOOLEAN NOT NULL, 
                score BOOLEAN NOT NULL,
                kills BOOLEAN NOT NULL, 
                deaaths BOOLEAN NOT NULL, 
                headshots BOOLEAN NOT NULL, 
                kd BOOLEAN NOT NULL, 
                button BOOLEAN NOT NULL, 
                PRIMARY KEY (`id`)
                );";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);

            add_option("rankme_scoreboard_db_version", $rankme_scoreboard_db_version);
        }

        global $rankme_profile_db_version;
        $table_name = $wpdb -> prefix . "rankme_profile";
        if ($wpdb -> get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $sql = "CREATE TABLE ". $table_name ." ( 
                id INT NOT NULL AUTO_INCREMENT, 
                host TEXT NOT NULL, 
                login TEXT NOT NULL, 
                password TEXT NOT NULL,
                db TEXT NOT NULL, 
                name TEXT NOT NULL, 
                showName BOOLEAN NOT NULL, 
                steam BOOLEAN NOT NULL, 
                score BOOLEAN NOT NULL, 
                kills BOOLEAN NOT NULL, 
                deaths BOOLEAN NOT NULL, 
                headshots BOOLEAN NOT NULL, 
                kd BOOLEAN NOT NULL, 
                assists BOOLEAN NOT NULL, 
                shots BOOLEAN NOT NULL, 
                hits BOOLEAN NOT NULL, 
                hostage_rescued BOOLEAN NOT NULL, 
                damage BOOLEAN NOT NULL, 
                mvp BOOLEAN NOT NULL, 
                match_isShow BOOLEAN NOT NULL, 
                win BOOLEAN NOT NULL, 
                draw BOOLEAN NOT NULL, 
                loose BOOLEAN NOT NULL, 
                models_isShow BOOLEAN NOT NULL, 
                head BOOLEAN NOT NULL, 
                chest BOOLEAN NOT NULL, 
                stomach BOOLEAN NOT NULL, 
                left_arm BOOLEAN NOT NULL, 
                right_arm BOOLEAN NOT NULL, 
                left_leg BOOLEAN NOT NULL, 
                right_leg BOOLEAN NOT NULL, 
                guns_isShow BOOLEAN NOT NULL, 
                ak47 BOOLEAN NOT NULL, 
                m4a1 BOOLEAN NOT NULL, 
                m4a1_silencer BOOLEAN NOT NULL, 
                knife BOOLEAN NOT NULL, 
                glock BOOLEAN NOT NULL, 
                hkp200 BOOLEAN NOT NULL, 
                usp_silencer BOOLEAN NOT NULL, 
                p250 BOOLEAN NOT NULL, 
                deagle BOOLEAN NOT NULL, 
                elite BOOLEAN NOT NULL, 
                fiveseven BOOLEAN NOT NULL, 
                tec9 BOOLEAN NOT NULL, 
                cz75a BOOLEAN NOT NULL, 
                revolver BOOLEAN NOT NULL, 
                nova BOOLEAN NOT NULL, 
                xm1014 BOOLEAN NOT NULL, 
                mag7 BOOLEAN NOT NULL, 
                sawedoff BOOLEAN NOT NULL, 
                bizon BOOLEAN NOT NULL, 
                mac10 BOOLEAN NOT NULL, 
                mp9 BOOLEAN NOT NULL, 
                mp7 BOOLEAN NOT NULL, 
                ump45 BOOLEAN NOT NULL, 
                p90 BOOLEAN NOT NULL, 
                galilar BOOLEAN NOT NULL, 
                scar20 BOOLEAN NOT NULL, 
                famas BOOLEAN NOT NULL, 
                aug BOOLEAN NOT NULL, 
                ssg08 BOOLEAN NOT NULL, 
                sg556 BOOLEAN NOT NULL, 
                awp BOOLEAN NOT NULL, 
                g3sg1 BOOLEAN NOT NULL, 
                m249 BOOLEAN NOT NULL, 
                negev BOOLEAN NOT NULL, 
                mp5sd BOOLEAN NOT NULL, 
                PRIMARY KEY (`id`)
                );";
            // require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);

            add_option("rankme_profile_db_version", $rankme_profile_db_version);
        }
    }

    function createScoreboard() {
        global $wpdb;
        $result = $wpdb -> get_results("SELECT * FROM ". $wpdb -> prefix ."rankme_scoreboard");

        foreach ($result as $key => $value) {
            $mysql = [
                "host" => $value -> host, 
                "login" => $value -> login,
                "password" => $value -> password,
                "database" => $value -> db
            ];

            $settings = [
                "start" => 0, 
                "end" => 25,
                "id" => $value -> id,
                "action" => $value -> action,
                "scoreboard" => [
                    "place" => $value -> place,
                    "name" => $value -> name,
                    "steam" => $value -> steam,
                    "score" => $value -> score,
                    "kills" => $value -> kills,
                    "deaths" => $value -> deaths,
                    "headshots" => $value -> headshots,
                    "kd" => $value -> kd,
                    "button" => $value -> button
                ]
            ];

            $rankme = new Rankme($mysql, $settings);
            // file_put_contents("1.txt", $rankme -> getShortcode());
        }
    }

    function getServers() {
        global $wpdb;
        $result = $wpdb -> get_results("SELECT id, host, db FROM ". $wpdb -> prefix ."rankme_scoreboard");
        $arr = [];

        foreach ($result as $key => $value) {
            $scoreboard = [
                "id" => $value -> id,
                "host" => $value -> host,
                "database" => $value -> db
            ];
            array_push($arr, $scoreboard);
        }
        
        return $arr;
    }

    function deleteDatabaseFromScoreboard($id) {
        global $wpdb;
        $result = $wpdb -> get_results("DELETE FROM ". $wpdb -> prefix ."rankme_scoreboard WHERE id = $id");
    }
    
    function addNewScoreboard($mysql, $settings) {
        global $wpdb;
        $mysql = "
        INSERT INTO ". $wpdb -> prefix ."rankme_scoreboard VALUES (
            NULL, 
            '$mysql[host]', 
            '$mysql[login]', 
            '$mysql[password]', 
            '$mysql[database]', 
            '$settings[action]', 
            '". $settings['scoreboard']['place'] ."', 
            '". $settings['scoreboard']['name'] ."', 
            '". $settings['scoreboard']['steam'] ."', 
            '". $settings['scoreboard']['score'] ."', 
            '". $settings['scoreboard']['kills'] ."', 
            '". $settings['scoreboard']['deaths'] ."', 
            '". $settings['scoreboard']['headshots'] ."', 
            '". $settings['scoreboard']['kd'] ."',  
            '". $settings['scoreboard']['button'] ."'
            )";
        echo $mysql;
        $result = $wpdb -> get_results($mysql);
    }

    function createProfilePage() {
        global $wpdb;
        $result = $wpdb -> get_results("SELECT * FROM ". $wpdb -> prefix ."rankme_profile");
        $mysql = [];

        foreach ($result as $key => $value) {
            $settings = [
                "name" => $value -> showName,
                "steam" => $value -> steam,
                "score" => $value -> score,
                "kills" => $value -> kills,
                "deaths" => $value -> deaths,
                "headshots" => $value -> headshots,
                "kd" => $value -> kd,
                "assists" => $value -> assists,
                "shots" => $value -> shots,
                "hits" => $value -> hits,
                "hostages_rescued" => $value -> hostages_rescued,
                "damage" => $value -> damage,
                "mvp" => $value -> mvp,
                "c4_planted" => $value -> c4_planted,
                "c4_exploded" => $value -> c4_exploded, 
                "c4_defused" => $value -> c4_defused,
                "first_blood" => $value -> first_blood,
                "no_scope" => $value -> no_scope,
                "no_scope_dis" => $value -> no_scope_dis,
                "match" => [
                    "isShow" => $value -> match_isShow,
                    "win" => $value -> win,
                    "draw" => $value -> draw,
                    "lose" => $value -> lose,
                    "rounds_tr" => $value -> rounds_tr,
                    "rounds_ct" => $value -> rounds_ct,
                    "ct_win" => $value -> ct_win,
                    "tr_win" => $value -> tr_win
                ],
                "models" => [
                    "isShow" => $value -> model_isShow,
                    "head" => $value -> head,
                    "chest" => $value -> chest,
                    "stomach" => $value -> stomach,
                    "left_arm" => $value -> left_arm,
                    "right_arm" => $value -> right_arm,
                    "left_leg" => $value -> left_leg,
                    "right_leg" => $value -> right_leg
                ],
                "guns" => [
                    "isShow" => $value -> guns_isShow,
                    "ak47" => $value -> ak47,
                    "m4a1" => $value -> m4a1,
                    "m4a1_silencer" => $value -> m4a1_silencer,
                    "knife" => $value -> knife,
                    "glock" => $value -> glock,
                    "hkp200" => $value -> hkp200,
                    "usp_silencer" => $value -> usp_silencer,
                    "p250" => $value -> p250,
                    "deagle" => $value -> deagle,
                    "elite" => $value -> elite,
                    "fiveseven" => $value -> fiveseven,
                    "tec9" => $value -> tec9,
                    "cz75a" => $value -> cz75a,
                    "revolver" => $value -> revolver,
                    "nova" => $value -> nova,
                    "xm1014" => $value -> xm1014,
                    "mag7" => $value -> mag7,
                    "sawedoff" => $value -> sawedoff,
                    "bizon" => $value -> bizon,
                    "mac10" => $value -> mac10,
                    "mp9" => $value -> mp9,
                    "mp7" => $value -> mp7,
                    "ump45" => $value -> ump45,
                    "p90" => $value -> p90,
                    "galilar" => $value -> galilar,
                    "scar20" => $value -> scar20,
                    "famas" => $value -> famas,
                    "aug" => $value -> aug,
                    "ssg08" => $value -> ssg08,
                    "sg556" => $value -> sg556,
                    "awp" => $value -> awp,
                    "g3sg1" => $value -> g3sg1,
                    "m249" => $value -> m249,
                    "negev" => $value -> negev,
                    "mp5sd" => $value -> mp5sd
                ]
            ];
            $localMysql = [
                "host" => $value -> host, 
                "login" => $value -> login,
                "password" => $value -> password,
                "database" => $value -> db,
                "name" => $value -> name,
                "settings" => $settings
            ];
            array_push($mysql, $localMysql);
        }
        $search = new RankmeSearch($mysql);
    }

?>