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

Flight::route('GET /volonteri', function (){
    $volunteers = Flight::pm()->get_all_university_students();
    Flight::json($volunteers);
});

Flight::route('GET /potvrdjeni_studenti', function (){
    $volunteers = Flight::pm()->get_confirmed_reservations();
    Flight::json($volunteers);
});

Flight::route('GET /srednjoskolci', function (){
    $school_students = Flight::pm()->get_all_highschool_students();
    Flight::json(array("students" => $school_students));
});

Flight::route('GET /osnovci', function (){
    $school_students = Flight::pm()->get_all_school_students();
    Flight::json(array("students" => $school_students));
});

Flight::route('GET /pristigle', function (){
    $new_reservations = Flight::pm()->get_new_reservations();
    Flight::json(array("reservations" => $new_reservations));
});

Flight::route('GET /pristigla_najava', function (){
    $new_reservations = Flight::pm()->get_new_reservations();
    Flight::json($new_reservations);
});

Flight::route('GET /vracene', function (){
    $returned_reservations = Flight::pm()->get_returned_reservations();
    Flight::json(array("reservations" => $returned_reservations));
});

Flight::route('GET /vracena_najava', function (){
    $returned_reservations = Flight::pm()->get_returned_reservations();
    Flight::json($returned_reservations);
});

Flight::route('GET /prihvacene', function (){
    $accepted_reservations = Flight::pm()->get_accepted_reservations();
    Flight::json(array("reservations" => $accepted_reservations));
});

Flight::route('GET /prihvacena_najava', function (){
    $accepted_reservations = Flight::pm()->get_accepted_reservations();
    Flight::json($accepted_reservations);
});

Flight::route('GET /gradovi', function (){
    $cities = Flight::pm()->get_all_cities();
    Flight::json($cities);
});

Flight::route('GET /aktivni', function (){
    $reports = Flight::pm()->get_all_reports();
    Flight::json(array( "reports" => $reports));
});

Flight::route('GET /kompletirani', function (){
    $reports = Flight::pm()->get_completed_reports();
    Flight::json(array( "reports" => $reports));
});

Flight::route('GET /aktivni_izvjestaj', function (){
    $reports = Flight::pm()->get_active_report_data();
    Flight::json($reports);
});

Flight::route('GET /kompletirani_izvjestaj', function (){
    $reports = Flight::pm()->get_completed_report_data();
    Flight::json($reports);
});

Flight::route('POST /kreiraj_najavu', function () {
    $student = Flight::request()->data->volunteer;
    $city = Flight::request()->data->city;
    $request = Flight::request();
    $studentArray = explode(" ", $student);
    $cityArray = explode(" ", $city);
    $input = array(
        "city_id" => $cityArray[0],
        "address" => $request->data->address,
        "for_date" => $request->data->for_date,
        "from_hour" => $request->data->from_hour,
        "to_hour" => $request->data->to_hour,
        "sidenote" => $request->data->sidenote
    );
    $dataArray = Flight::pm()->create_reservation($input, $studentArray[0]);
    Flight::pm()->add_mentor($dataArray);
});

Flight::route('POST /kreiraj_izvjestaj', function () {
    $student = Flight::request()->data->volunteer;
    $request = Flight::request();
    $studentArray = explode(" ", $student);
    $input = array(
        "goal_of_session" => $request->data->goal_of_session,
        "volunteer_activities" => $request->data->volunteer_activities,
        "sidenote" => $request->data->sidenote,
        "attendance_comment" => $request->data->goal_of_session,
        "grade" => $request->data->grade,
        "administration_comment" => $request->data->administration_comment,
        "reservation_id" => $studentArray[0]
    );
    Flight::pm()->create_report($input);
    Flight::pm()->finalize_reservation($studentArray[0]);
});

Flight::route('DELETE /obrisi_najavu/@id', function($id){
    Flight::pm()->delete_reservation($id);
});

Flight::route('DELETE /obrisi_izvjestaj/@id', function($id){
    Flight::pm()->delete_report($id);
    Flight::pm()->update_reservation($id);
});

Flight::route('POST /pregledaj_najavu/', function(){
    $student = Flight::request()->data->volunteer;
    $city = Flight::request()->data->city;
    $acceptedString = Flight::request()->data->is_accepted;
    $is_accepted = 0;
    if ($acceptedString === "Da") {
        $is_accepted = 1;
    }
    $request = Flight::request();
    $studentArray = explode(" ", $student);
    $cityArray = explode(" ", $city);
    $input = array(
        "city_id" => $cityArray[0],
        "address" => $request->data->address,
        "for_date" => $request->data->for_date,
        "from_hour" => $request->data->from_hour,
        "to_hour" => $request->data->to_hour,
        "sidenote" => $request->data->sidenote,
        "is_accepted" => $is_accepted,
        "reservation_id" => $studentArray[0]
    );
    Flight::pm()->check_reservation($input);
});

Flight::route('POST /pregledaj_izvjestaj/', function(){
    $student = Flight::request()->data->volunteer;
    $acceptedString = Flight::request()->data->is_accepted;
    $is_accepted = 0;
    if ($acceptedString === "Da") {
        $is_accepted = 1;
    }
    $request = Flight::request();
    $studentArray = explode(" ", $student);
    $input = array(
        "goal_of_session" => $request->data->goal_of_session,
        "volunteer_activities" => $request->data->volunteer_activities,
        "sidenote" => $request->data->sidenote,
        "attendance_comment" => $request->data->goal_of_session,
        "grade" => $request->data->grade,
        "administration_comment" => $request->data->administration_comment,
        "reservation_id" => $studentArray[0],
        "is_accepted" => $is_accepted
    );
    Flight::pm()->check_report($input);
});




Flight::route('DELETE /university_students/@id', function($id){
    Flight::pm()->delete_university_student($id);
});

Flight::start();

?>