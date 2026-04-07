<?php
class AlbumController extends Controller {
    public function index() {
        $this->logPageVisit();
        $photos = Photo::PHOTOS;
        $this->view->render('pages/album.php', 'Фотоальбом', ['photos' => $photos]);
    }
}