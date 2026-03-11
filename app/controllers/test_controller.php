<?php
class TestController extends Controller {
    public function index() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleTestForm();
        } else {
            $this->view->render('pages/test-odm.php', 'Тест по дискретной математике');
        }
    }

    private function handleTestForm() {
        $validator = new ResultsVerification();
        $validator->setRule('ФИО', 'isNotEmpty');
        $validator->setRule('Группа', 'isNotEmpty');
        $validator->setRule('Q1_Графы', 'isNotEmpty');
        $validator->setRule('Q2_Множества', 'isNotEmpty');
        $validator->setRule('Q3_Ответ', 'isNotEmpty');

        if (!$validator->validateTest($_POST)) {
            $errors = $validator->getErrors();
            $this->view->render('pages/test-odm.php', 'Тест', ['errors' => $errors]);
        } else {
            $results = $validator->getResults();
            $this->view->render('pages/test-odm.php', 'Результат теста', ['results' => $results]);
        }
    }
}