<?php

    require "rankme.class.php";
    require "search-rankme.class.php";
    require "admin.php";

    if (!defined('ABSPATH')) exit;

    /*
        Plugin Name: CSGO Rankme
        Plugin URI: https://github.com/Munoon/CSGO-Rankme-Web-Interface-for-WordPress
        Description: Plugin allow show information about players from database of plugin Rankme (by Kento). It is support scoreboard, search, players profile and using few databases.
        Version: 1.0
        Author: Munoon
        Author URI: https://github.com/Munoon
    */

    /*
        Copyright 2019 Munoon (email: munoongg@gmail.com)

        This program is free software; you can redistribute it and/or modify
        it under the terms of the GNU General Public License as published by
        the Free Software Foundation; either version 2 of the License, or
        (at your option) any later version.

        This program is distributed in the hope that it will be useful,
        but WITHOUT ANY WARRANTY; without even the implied warranty of
        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
        GNU General Public License for more details.

        You should have received a copy of the GNU General Public License
        along with this program; if not, write to the Free Software
        Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    */

    register_activation_hook(__FILE__, 'rankme_createScoreboardTable');

    add_action('plugins_loaded', 'rankme_init');
    add_action('admin_menu', 'rankme_add_pages');
    add_action('wp_ajax_rankme', 'rankme_scoreboard_more');
    add_action('wp_ajax_nopriv_rankme', 'rankme_scoreboard_more');

    function rankme_init() {
        rankme_createScoreboard();
        rankme_createProfilePage();
    }

    function rankme_scoreboard_more() {
        $scoreboards = rankme_createScoreboard();
        $scoreboards[$_GET['id']] -> getJson($_GET['start'], $_GET['count']);
        wp_die();
    }

?>