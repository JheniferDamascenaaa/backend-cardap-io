<?php
require_once __DIR__ . '/../models/RestaurantModel.php';

class ReviewController {
    private $model;

    public function __construct() {
        $this->model = new ReviewModel();
    }

    public function index() {
        echo json_encode($this->model->getAll());
    }

    public function show($id) {
        echo json_encode($this->model->getById($id));
    }

    public function store() {}
    public function update($id) {}
    public function destroy($id) {}
}
