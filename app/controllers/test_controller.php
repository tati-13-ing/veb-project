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
            
            // Сохраняем результаты теста в базу данных
            $this->saveTestResult($_POST, $results);
            
            $this->view->render('pages/test-odm.php', 'Результат теста', ['results' => $results]);
        }
    }
    
    /**
     * Сохраняет результаты тестирования в базу данных
     */
    private function saveTestResult($post, $results)
    {
        $testResult = new TestResultModel();
        $testResult->fio = $post['ФИО'];
        $testResult->group_name = $post['Группа'];
        $testResult->q1_result = $results['q1'];
        $testResult->q2_result = $results['q2'];
        $testResult->q3_result = $results['q3'];
        
        // Подсчитываем количество правильных ответов
        $correct = 0;
        if ($results['q1'] === 'Верно') $correct++;
        if ($results['q2'] === 'Верно') $correct++;
        if ($results['q3'] === 'Верно') $correct++;
        $testResult->total_correct = $correct;
        
        $testResult->test_date = date('Y-m-d H:i:s');
        $testResult->save();
    }
    
    /**
     * Просмотр результатов тестирования (для администратора)
     */
    public function results()
    {
        // Получаем все результаты с пагинацией
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        
        $results = TestResultModel::findPaginated($offset, $perPage, 'test_date', 'DESC');
        $total = TestResultModel::count();
        $totalPages = ceil($total / $perPage);
        
        $this->view->render('pages/test_results.php', 'Результаты тестирования', [
            'results' => $results,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalResults' => $total
        ]);
    }
}