<?php
class AlbumController extends Controller {
    public function index() {
        $photos = Photo::PHOTOS;
        $this->view->render('pages/album.php', 'Фотоальбом', ['photos' => $photos]);
    }
}