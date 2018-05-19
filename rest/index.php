<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/1/18
 * Time: 9:48 PM
 */

require '../vendor/autoload.php';
require_once 'FrontPageAPI.php';
require_once 'PersistenceManager.class.php';
require_once 'Config.class.php';

Flight::register('frontpage', 'FrontPageAPI');
Flight::register('pm', 'PersistenceManager', [Config::DB]);

Flight::route('GET /front_page', function() {
    Flight::json(Flight::frontpage()->get_data());
});

Flight::route('GET /university_students', function (){
   $uni_students = Flight::pm()->get_all_university_students();
   Flight::json($uni_students);
});

Flight::route('GET /school_students', function (){
    $users = Flight::pm()->get_all_school_students();
    Flight::json($users);
});

Flight::start();

?>