<?php
class IndexController extends Controller {
    public function index() {
        $this->logPageVisit();
        $this->view->render('pages/index.php', 'Главная');
    }
}