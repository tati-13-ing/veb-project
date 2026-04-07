<?php
class StudyController extends Controller {
    public function index() {
        $this->logPageVisit();
        $this->view->render('pages/study.php', 'Учеба');
    }
}