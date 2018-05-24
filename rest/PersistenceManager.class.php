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

    public function get_all_school_students(){
        $query = "SELECT stu.id, stu.name, surname, c.city AS city, address, email, tel1, gpa, school.name AS facility_name, year_of_study 
                  FROM SchoolStudents AS stu 
                  INNER JOIN Cities AS c ON stu.city_id = c.id
                  INNER JOIN Schools AS school ON stu.school_id=school.id";
        return $this->pdo->query($query)->fetchAll();
    }

    public function get_all_highschool_students(){
        $query = "SELECT * FROM SchoolStudents AND Schools";
        return $this->pdo->query($query)->fetchAll();
    }

    public function get_user_by_id($id){

    }

    public function update_user($id, $user){

    }

    public function get_new_reservations() {
        $query = "SELECT res.id AS reservation_id, time_of_reservation, for_date, from_hour, to_hour, res.address AS address, sidenote, is_accepted, c.city AS city, 
                  men.id AS mentor_id, stu.name AS name, stu.surname AS surname 
                  FROM Reservations AS res INNER JOIN Cities AS c ON res.city_id = c.id 
                  INNER JOIN Mentors AS men ON men.reservation_id = res.id 
                  INNER JOIN UniversityStudents AS stu ON stu.id = men.student_id
                  WHERE res.is_accepted IS NULL";
        return $this->pdo->query($query)->fetchAll();
    }

    public function get_returned_reservations() {
        $query = "SELECT res.id AS reservation_id, time_of_reservation, for_date, from_hour, to_hour, res.address AS address, sidenote, is_accepted, c.city AS city, 
                  men.id AS mentor_id, stu.name AS name, stu.surname AS surname 
                  FROM Reservations AS res INNER JOIN Cities AS c ON res.city_id = c.id 
                  INNER JOIN Mentors AS men ON men.reservation_id = res.id 
                  INNER JOIN UniversityStudents AS stu ON stu.id = men.student_id
                  WHERE res.is_accepted = 0";
        return $this->pdo->query($query)->fetchAll();
    }

    public function delete_university_student($id){
        $query = "DELETE FROM UniversityStudents WHERE id = ?";
        $statement = $this->pdo->prepare($query);
        $statement->execute([$id]);
    }

    public function get_all_cities() {
        $query = "SELECT city FROM Cities";
        return $this->pdo->query($query)->fetchAll();
    }
}

?>