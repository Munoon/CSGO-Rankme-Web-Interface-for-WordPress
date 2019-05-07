<?php

    require "rankme.class.php";
    require "search-rankme.class.php";
    require "database.php";

    /*
    Plugin Name: CSGO Rankme
    Plugin URI: https://github.com/Munoon/CSGO-Rankme-Web-Interface-for-WordPress
    Description: Plugin allow show information about players from database of plugin Rankme (by Kento). It is support scoreboard, search, players profile and using few databases.
    Version: 1.0
    Author: Munoon
    Author URI: https://github.com/Munoon
    */

    register_activation_hook(__FILE__, 'rankme_createScoreboardTable');
    createScoreboard();
    createProfilePage();


?>