<?php
class Course {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllCourses() {
        $stmt = $this->pdo->query("SELECT * FROM courses");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCourseById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM courses WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createCourse($name, $description) {
        $stmt = $this->pdo->prepare("INSERT INTO courses (name, description) VALUES (:name, :description)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }

    public function updateCourse($id, $name, $description) {
        $stmt = $this->pdo->prepare("UPDATE courses SET name = :name, description = :description WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
    }

    public function deleteCourse($id) {
        $stmt = $this->pdo->prepare("DELETE FROM courses WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}
