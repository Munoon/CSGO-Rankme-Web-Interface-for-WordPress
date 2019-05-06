<?php

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
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);

            add_option("rankme_profile_db_version", $rankme_profile_db_version);
        }
    }

    function createScoreboard() {
        include_once($_SERVER['DOCUMENT_ROOT'].'/wordpress/wp-config.php');
        global $wpdb;
        $result = $wpdb -> get_results("SELECT * FROM wp_rankme_scoreboard");

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

?>