<?php

require_once 'api/controllers/CourseController.php';
require_once 'api/controllers/StudentController.php';

// Recupera tutti i corsi
if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SERVER['REQUEST_URI'] == '/api/corsi') {
    $controller = new CourseController($pdo);
    $controller->getCourses();
}

// Recupera un corso per ID
if (preg_match('/^\/api\/corsi\/(\d+)$/', $_SERVER['REQUEST_URI'], $matches)) {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $controller = new CourseController($pdo);
        $controller->getCourse($matches[1]);
    }
}

// Crea un nuovo corso
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SERVER['REQUEST_URI'] == '/api/corsi') {
    $controller = new CourseController($pdo);
    $controller->createCourse();
}

// Aggiorna un corso
if (preg_match('/^\/api\/corsi\/(\d+)$/', $_SERVER['REQUEST_URI'], $matches)) {
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $controller = new CourseController($pdo);
        $controller->updateCourse($matches[1]);
    }
}

// Elimina un corso
if (preg_match('/^\/api\/corsi\/(\d+)$/', $_SERVER['REQUEST_URI'], $matches)) {
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        $controller = new CourseController($pdo);
        $controller->deleteCourse($matches[1]);
    }
}
