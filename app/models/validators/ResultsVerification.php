<?php

class ResultsVerification extends CustomFormValidation
{
    protected $results = [];

    /**
     * Проверка ответа типа RadioButton (один вариант из нескольких)
     */
    public function checkRadio($userAnswer, $correctAnswer, $questionName = '')
    {
        $result = ($userAnswer === $correctAnswer);
        $this->results[$questionName] = $result ? 'Верно' : 'Неверно';
        return $result;
    }

    /**
     * Проверка ответа типа ComboBox (select, один вариант)
     */
    public function checkCombo($userAnswer, $correctAnswer, $questionName = '')
    {
        return $this->checkRadio($userAnswer, $correctAnswer, $questionName);
    }

    /**
     * Проверка ответа типа однострочный текст (с нормализацией)
     *
     * @param string $userAnswer
     * @param string $correctAnswer
     * @param bool   $caseInsensitive  Игнорировать регистр (используется my_strtolower)
     * @param bool   $ignoreSpaces     Игнорировать пробелы
     * @param string $questionName
     * @return bool
     */
    public function checkText($userAnswer, $correctAnswer, $caseInsensitive = true, $ignoreSpaces = true, $questionName = '')
    {
        $user = trim($userAnswer);
        $correct = trim($correctAnswer);

        if ($caseInsensitive) {
            // Используем глобальную функцию my_strtolower (определена в page_controller.php)
            $user = my_strtolower($user);
            $correct = my_strtolower($correct);
        }

        if ($ignoreSpaces) {
            $user = preg_replace('/\s+/', '', $user);
            $correct = preg_replace('/\s+/', '', $correct);
        }

        $result = ($user === $correct);
        $this->results[$questionName] = $result ? 'Верно' : 'Неверно';
        return $result;
    }

    /**
     * Проверка ответа типа Checkbox (множественный выбор)
     */
    public function checkCheckbox($userAnswers, $correctAnswers, $questionName = '')
    {
        if (!is_array($userAnswers)) {
            $userAnswers = [];
        }
        sort($userAnswers);
        sort($correctAnswers);
        $result = ($userAnswers == $correctAnswers);
        $this->results[$questionName] = $result ? 'Верно' : 'Неверно';
        return $result;
    }

    /**
     * Переопределяем метод проверки теста, используя специализированные методы
     */
    public function validateTest($post)
    {
        // Сначала базовая валидация (наследуется от CustomFormValidation)
        if (!parent::validate($post)) {
            return false;
        }

        $q1 = isset($post['Q1_Графы']) ? $post['Q1_Графы'] : [];
        $q2 = isset($post['Q2_Множества']) ? $post['Q2_Множества'] : '';
        $q3 = isset($post['Q3_Ответ']) ? trim($post['Q3_Ответ']) : '';

        $correct_q1 = ['Ориентированный граф', 'Неориентированный граф', 'Дерево'];
        $correct_q2 = 'Операция объединения A ∪ B содержит элементы, входящие хотя бы в одно множество';
        $correct_q3 = 'тридцать два';

        $this->checkCheckbox($q1, $correct_q1, 'q1');
        $this->checkCombo($q2, $correct_q2, 'q2');
        // Исправлено: передаём все 5 аргументов
        $this->checkText($q3, $correct_q3, true, true, 'q3');

        return true;
    }

    public function getResults()
    {
        return $this->results;
    }

    public function showResults()
    {
        if (empty($this->results)) {
            echo '<p>Результаты отсутствуют.</p>';
            return;
        }
        echo '<div class="test-results">';
        echo '<h3>Результаты:</h3>';
        foreach ($this->results as $question => $result) {
            echo '<p>' . htmlspecialchars($question) . ': ' . htmlspecialchars($result) . '</p>';
        }
        echo '</div>';
    }
}