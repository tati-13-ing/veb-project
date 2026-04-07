<?php
class AboutController extends Controller {
    public function index() {
        $this->logPageVisit();
        $this->view->render('pages/about.php', 'Обо мне');
    }
}
