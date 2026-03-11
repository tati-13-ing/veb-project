<?php
class HistoryController extends Controller {
    public function index() {
        $this->view->render('pages/history.php', 'История просмотра');
    }
}