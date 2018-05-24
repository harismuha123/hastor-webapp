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

Flight::route('GET /studenti', function (){
   $uni_students = Flight::pm()->get_all_university_students();
   Flight::json(array( "students" => $uni_students ));
});

Flight::route('GET /osnovci', function (){
    $school_students = Flight::pm()->get_all_school_students();
    Flight::json(array("students" => $school_students));
});

Flight::route('GET /pristigle', function (){
    $new_reservations = Flight::pm()->get_new_reservations();
    Flight::json(array("reservations" => $new_reservations));
});

Flight::route('GET /vracene', function (){
    $returned_reservations = Flight::pm()->get_returned_reservations();
    Flight::json(array("reservations" => $returned_reservations));
});

Flight::route('GET /prihvacene', function (){
    $returned_reservations = Flight::pm()->get_returned_reservations();
    Flight::json(array("reservations" => $returned_reservations));
});

Flight::route('GET /gradovi', function (){
    $cities = Flight::pm()->get_all_cities();
    Flight::json($cities);
});

Flight::route('DELETE /university_students/@id', function($id){
    Flight::pm()->delete_university_student($id);
});

Flight::start();

?>