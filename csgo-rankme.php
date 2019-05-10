<?php

    require "rankme.class.php";
    require "search-rankme.class.php";
    require "admin.php";

    /*
    Plugin Name: CSGO Rankme
    Plugin URI: https://github.com/Munoon/CSGO-Rankme-Web-Interface-for-WordPress
    Description: Plugin allow show information about players from database of plugin Rankme (by Kento). It is support scoreboard, search, players profile and using few databases.
    Version: 1.0
    Author: Munoon
    Author URI: https://github.com/Munoon
    */

    register_activation_hook(__FILE__, 'rankme_createScoreboardTable');

    add_action('admin_menu', 'rankme_add_pages');
    add_action('wp_ajax_rankme', 'rankme_scoreboard_more');
    add_action('wp_ajax_nopriv_rankme', 'rankme_scoreboard_more');

    createScoreboard();
    createProfilePage();

    function rankme_scoreboard_more() {
        $scoreboards = createScoreboard();
        $scoreboards[$_GET['id']] -> getJson($_GET['start'], $_GET['count']);
        wp_die();
    }

?>