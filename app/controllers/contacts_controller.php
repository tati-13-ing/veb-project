<?php
class ContactsController extends Controller {
    public function index() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleContactsForm();
        } else {
            $this->view->render('pages/contacts.php', 'Контакты');
        }
    }

    private function handleContactsForm() {
        $validator = new FormValidation();
        $validator->setRule('ФИО', 'isNotEmpty');
        $validator->setRule('Телефон', 'isNotEmpty');
        $validator->setRule('Email', 'isNotEmpty');
        $validator->setRule('Email', 'isEmail');
        $validator->setRule('Сообщение', 'isNotEmpty');

        if (!$validator->validate($_POST)) {
            $errors = $validator->getErrors();
            $this->view->render('pages/contacts.php', 'Контакты', ['errors' => $errors]);
        } else {
            $success = 'Сообщение отправлено (демо-режим)';
            $this->view->render('pages/contacts.php', 'Контакты', ['success' => $success]);
        }
    }
}