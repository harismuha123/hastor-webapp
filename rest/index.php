<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/1/18
 * Time: 9:48 PM
 */

require '../vendor/autoload.php';
require_once 'FrontPageAPI.php';

Flight::register('frontpage', 'FrontPageAPI');

Flight::route('GET /front_page', function() {
    Flight::json(Flight::frontpage()->get_data());
});

Flight::start();
