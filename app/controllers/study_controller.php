<?php
class StudyController extends Controller {
    public function index() {
        $this->view->render('pages/study.php', 'Учеба');
    }
}