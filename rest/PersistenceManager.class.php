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
        $query = "SELECT * FROM UniversityStudents";
        return $this->pdo->query($query)->fetchAll();
    }

    public function get_all_school_students(){
        $query = "SELECT * FROM SchoolStudents";
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

    public function delete_university_student($id){
        $query = "DELETE FROM UniversityStudents WHERE id = ?";
        $statement = $this->pdo->prepare($query);
        $statement->execute([$id]);
    }
}

?>