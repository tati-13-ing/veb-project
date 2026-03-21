<?php
class InterestsController extends Controller {
    public function index() {
        $categories = Interest::CATEGORIES;
        $items = Interest::ITEMS;
        $this->view->render('pages/interests.php', 'Мои интересы', [
            'categories' => $categories,
            'items'      => $items
        ]);
    }
}  