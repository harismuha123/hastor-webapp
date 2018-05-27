<?php

class PersistenceManager {
    private $pdo;

    public function __construct($params) {
        $this->pdo = new PDO('mysql:host='.$params['host'].';dbname='.$params['scheme'], $params['username'], $params['password']);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    public function add_user($user){

    }

    public function get_all_university_students(){
        $query = "SELECT stu.id, stu.name, surname, c.city AS city, address, email, tel1, gpa, uni.name AS facility_name, year_of_study 
                  FROM UniversityStudents AS stu 
                  INNER JOIN Cities AS c ON stu.city_id = c.id
                  INNER JOIN Universities AS uni ON stu.university_id=uni.id";

        return $this->pdo->query($query)->fetchAll();
    }

    public function get_confirmed_reservations(){
        $query = "SELECT res.id AS reservation_id, stu.name AS name, stu.surname AS surname 
                  FROM Reservations AS res 
                  INNER JOIN Mentors AS men ON men.reservation_id = res.id 
                  INNER JOIN UniversityStudents AS stu ON men.student_id = stu.id
                  WHERE res.is_accepted = 1 AND res.report_accepted IS NULL";
        return $this->pdo->query($query)->fetchAll();
    }

    public function get_all_school_students(){
        $query = "SELECT stu.id, stu.name, surname, c.city AS city, address, email, tel1, gpa, school.name AS facility_name, year_of_study 
                  FROM SchoolStudents AS stu 
                  INNER JOIN Cities AS c ON stu.city_id = c.id
                  INNER JOIN Schools AS school ON stu.school_id=school.id AND school.is_highschool = 0";
        return $this->pdo->query($query)->fetchAll();
    }

    public function get_all_highschool_students(){
        $query = "SELECT stu.id, stu.name, surname, c.city AS city, address, email, tel1, gpa, school.name AS facility_name, year_of_study 
                  FROM SchoolStudents AS stu 
                  INNER JOIN Cities AS c ON stu.city_id = c.id
                  INNER JOIN Schools AS school ON stu.school_id=school.id AND school.is_highschool = 1";
        return $this->pdo->query($query)->fetchAll();
    }

    public function get_user_by_id($id){

    }

    public function update_user($id, $user){

    }

    public function get_new_reservations() {
        $query = "SELECT res.id AS reservation_id, time_of_reservation, for_date, from_hour, to_hour, res.address AS address, sidenote, is_accepted, c.city AS city, c.id AS city_id,
                  men.id AS mentor_id, stu.name AS name, stu.surname AS surname 
                  FROM Reservations AS res INNER JOIN Cities AS c ON res.city_id = c.id 
                  INNER JOIN Mentors AS men ON men.reservation_id = res.id 
                  INNER JOIN UniversityStudents AS stu ON stu.id = men.student_id
                  WHERE res.is_accepted IS NULL AND res.report_accepted IS NULL";
        return $this->pdo->query($query)->fetchAll();
    }


    public function get_returned_reservations() {
        $query = "SELECT res.id AS reservation_id, time_of_reservation, for_date, from_hour, to_hour, res.address AS address, sidenote, is_accepted, c.city AS city, c.id AS city_id,
                  men.id AS mentor_id, stu.name AS name, stu.surname AS surname 
                  FROM Reservations AS res INNER JOIN Cities AS c ON res.city_id = c.id 
                  INNER JOIN Mentors AS men ON men.reservation_id = res.id 
                  INNER JOIN UniversityStudents AS stu ON stu.id = men.student_id
                  WHERE res.is_accepted = 0 AND res.report_accepted IS NULL";
        return $this->pdo->query($query)->fetchAll();
    }

    public function get_accepted_reservations() {
        $query = "SELECT res.id AS reservation_id, time_of_reservation, for_date, from_hour, to_hour, res.address AS address, sidenote, is_accepted, c.city AS city, c.id AS city_id,
                  men.id AS mentor_id, stu.name AS name, stu.surname AS surname 
                  FROM Reservations AS res INNER JOIN Cities AS c ON res.city_id = c.id 
                  INNER JOIN Mentors AS men ON men.reservation_id = res.id 
                  INNER JOIN UniversityStudents AS stu ON stu.id = men.student_id
                  WHERE res.is_accepted = 1 AND res.report_accepted IS NULL";
        return $this->pdo->query($query)->fetchAll();
    }

    public function delete_university_student($id){
        $query = "DELETE FROM UniversityStudents WHERE id = ?";
        $statement = $this->pdo->prepare($query);
        $statement->execute([$id]);
    }

    public function get_all_cities() {
        $query = "SELECT id, city FROM Cities";
        return $this->pdo->query($query)->fetchAll();
    }


    public function create_reservation($input, $student_id) {
        $query = "INSERT INTO Reservations(time_of_reservation, for_date, from_hour, to_hour, address, sidenote, city_id)
                  VALUES(NOW(), :for_date, :from_hour, :to_hour, :address, :sidenote, :city_id)";
        $statement = $this->pdo->prepare($query);
        $statement->execute($input);
        $reservation_id = $this->pdo->lastInsertId();
        $newArray = array(
            "student_id" => $student_id,
            "reservation_id" => $reservation_id
        );
        return $newArray;
    }

    public function create_report($input) {
        $query = "INSERT INTO Reports(date_sent, goal_of_session, volunteer_activities, sidenote, attendance_comment, grade, administration_comment, reservation_id)
                  VALUES(CURRENT_TIMESTAMP, :goal_of_session, :volunteer_activities, :sidenote, :attendance_comment, :grade, :administration_comment, :reservation_id)";
        $statement = $this->pdo->prepare($query);
        $statement->execute($input);
    }

    public function add_mentor($input) {
        $query = "INSERT INTO Mentors(reservation_id, student_id)
                  VALUES(:reservation_id, :student_id)";
        $statement = $this->pdo->prepare($query);
        $statement->execute($input);
    }

    public function delete_reservation($id) {
        $input = array();
        array_push($input, $id, $id);
        $query = "DELETE FROM Mentors WHERE reservation_id = ?;
                  DELETE FROM Reservations WHERE id = ?";
        $statement = $this->pdo->prepare($query);
        $statement->execute($input);
    }

    public function check_reservation($input) {
        $query = "UPDATE Reservations
                  SET city_id = :city_id, address = :address, for_date = :for_date, from_hour = :from_hour, to_hour = :to_hour, sidenote = :sidenote, is_accepted = :is_accepted
                  WHERE id = :reservation_id";
        $statement = $this->pdo->prepare($query);
        $statement->execute($input);
        $id = $this->pdo->lastInsertId();
        return $id;
    }

    public function finalize_reservation($id) {
        $query = "UPDATE Reservations
                  SET report_accepted = 1
                  WHERE id = ?";
        $statement = $this->pdo->prepare($query);
        $statement->execute([$id]);
;
    }

    public function get_all_reports() {
        $query = "SELECT stu.name AS name, stu.surname AS surname, c.city AS city, res.address AS address,
                  res.for_date AS for_date, res.from_hour AS from_hour, res.to_hour AS to_hour, res.id AS reservation_id,
                  rep.id AS report_id
                  FROM Reports AS rep
                  INNER JOIN Reservations AS res ON rep.reservation_id = res.id
                  INNER JOIN Cities AS c ON res.city_id = c.id
                  INNER JOIN Mentors AS mentor ON res.id = mentor.reservation_id
                  INNER JOIN UniversityStudents as stu ON mentor.student_id = stu.id
                  WHERE res.is_accepted = 1";
        return $this->pdo->query($query)->fetchAll();
    }
    public function get_completed_reports() {
        $query = "SELECT stu.name AS name, stu.surname AS surname, c.city AS city, res.address AS address,
                  res.for_date AS for_date, res.from_hour AS from_hour, res.to_hour AS to_hour, res.id AS reservation_id,
                  rep.id AS report_id
                  FROM Reports AS rep
                  INNER JOIN Reservations AS res ON rep.reservation_id = res.id
                  INNER JOIN Cities AS c ON res.city_id = c.id
                  INNER JOIN Mentors AS mentor ON res.id = mentor.reservation_id
                  INNER JOIN UniversityStudents as stu ON mentor.student_id = stu.id
                  WHERE res.is_accepted = 1 AND rep.is_accepted = 1";
        return $this->pdo->query($query)->fetchAll();
    }

    public function get_report_data($id) {
        $query = "SELECT res.id AS reservation_id, stu.name AS name, stu.surname AS surname, c.city AS city, c.id AS city_id, res.address AS address 
                  FROM Reservations AS res 
                  INNER JOIN Mentors AS mentor ON mentor.reservation_id = res.id 
                  INNER JOIN UniversityStudents AS stu ON mentor.student_id = stu.id 
                  INNER JOIN Cities AS c ON res.city_id = c.id
                  WHERE res.id = ?";

    }

}

?>