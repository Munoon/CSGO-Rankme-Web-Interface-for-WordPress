<?php
    require "rankme.class.php";
    require "search-rankme.class.php";

    /*
    Plugin Name: CSGO Rankme
    Plugin URI: https://github.com/Munoon/CSGO-Rankme-Web-Interface-for-WordPress
    Description: Plugin allow show information about players from database of plugin Rankme (by Kento). It is support scoreboard, search, players profile and using few databases.
    Version: 1.0
    Author: Munoon
    Author URI: https://github.com/Munoon
    */

    $rankme_mysql = [
        "host" => "", 
        "login" => "",
        "password" => "",
        "database" => ""
    ];
    $rankme_settings = [
        "start" => 0, 
        "end" => 25,
        "action" => "../rankme-search",
        "scoreboard" => [
            "place" => true,
            "name" => true,
            "steam" => true,
            "score" => true,
            "kills" => false,
            "deaths" => false,
            "headshots" => false,
            "kd" => true,
            "button" => true
        ]
    ];
    $rankme = new Rankme($rankme_mysql, $rankme_settings);

    $search_rankme_settings = [
        "name" => true,
        "steam" => true,
        "score" => true,
        "kills" => true,
        "deaths" => true,
        "headshots" => true,
        "kd" => true,
        "assists" => true,
        "shots" => true,
        "hits" => true,
        "hostages_rescued" => true,
        "damage" => true,
        "mvp" => true,
        "c4_planted" => true,
        "c4_exploded" => true, 
        "c4_defused" => true,
        "first_blood" => true,
        "no_scope" => true,
        "no_scope_dis" => true,
        "match" => [
            "isShow" => true,
            "win" => true,
            "draw" => true,
            "lose" => true,
            "rounds_tr" => true,
            "rounds_ct" => true,
            "ct_win" => true,
            "tr_win" => true
        ],
        "models" => [
            "isShow" => true,
            "head" => true,
            "chest" => true,
            "stomach" => true,
            "left_arm" => true,
            "right_arm" => true,
            "left_leg" => true,
            "right_leg" => true
        ],
        "guns" => [
            "isShow" => true,
            "ak47" => true,
            "m4a1" => true,
            "m4a1_silencer" => true,
            "knife" => true,
            "glock" => true,
            "hkp200" => true,
            "usp_silencer" => true,
            "p250" => true,
            "deagle" => true,
            "elite" => true,
            "fiveseven" => true,
            "tec9" => true,
            "cz75a" => true,
            "revolver" => true,
            "nova" => true,
            "xm1014" => true,
            "mag7" => true,
            "sawedoff" => true,
            "bizon" => true,
            "mac10" => true,
            "mp9" => true,
            "mp7" => true,
            "ump45" => true,
            "p90" => true,
            "galilar" => true,
            "scar20" => true,
            "famas" => true,
            "aug" => true,
            "ssg08" => true,
            "sg556" => true,
            "awp" => true,
            "g3sg1" => true,
            "m249" => true,
            "negev" => true,
            "mp5sd" => true
        ]
    ];
    $search_rankme_settings2 = [
        "name" => false,
        "steam" => true,
        "score" => true,
        "kills" => true,
        "deaths" => true,
        "headshots" => true,
        "kd" => true,
        "assists" => true,
        "shots" => true,
        "hits" => true,
        "hostage_rescued" => true,
        "damage" => true,
        "mvp" => true,
        "match" => [
            "isShow" => true,
            "win" => true,
            "draw" => true,
            "loose" => true
        ],
        "models" => [
            "isShow" => true,
            "head" => true,
            "chest" => true,
            "stomach" => true,
            "left_arm" => true,
            "right_arm" => true,
            "left_leg" => true,
            "right_leg" => true
        ],
        "guns" => [
            "isShow" => true,
            "ak47" => true,
            "m4a1" => true,
            "m4a1_silencer" => true,
            "knife" => true,
            "glock" => true,
            "hkp200" => true,
            "usp_silencer" => true,
            "p250" => true,
            "deagle" => true,
            "elite" => true,
            "fiveseven" => true,
            "tec9" => true,
            "cz75a" => true,
            "revolver" => true,
            "nova" => true,
            "xm1014" => true,
            "mag7" => true,
            "sawedoff" => true,
            "bizon" => true,
            "mac10" => true,
            "mp9" => true,
            "mp7" => true,
            "ump45" => true,
            "p90" => true,
            "galilar" => true,
            "scar20" => true,
            "famas" => true,
            "aug" => true,
            "ssg08" => true,
            "sg556" => true,
            "awp" => true,
            "g3sg1" => true,
            "m249" => true,
            "negev" => true,
            "mp5sd" => true
        ]
    ];
    $search_rankme_mysql = [
        [
            "host" => "", 
            "login" => "",
            "password" => "",
            "database" => "",
            "name" => "scoreboard1",
            "settings" => $search_rankme_settings
        ], 
        [
            "host" => "", 
            "login" => "",
            "password" => "",
            "database" => "",
            "name" => "scoreboard2",
            "settings" => $search_rankme_settings2
        ]
    ];
    $rankmeSearch = new RankmeSearch($search_rankme_mysql);
?>