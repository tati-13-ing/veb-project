<?php

require_once 'FormValidation.php'; // или полагаться на автозагрузку

class CustomFormValidation extends FormValidation
{
    /**
     * @var array Результаты проверки ответов теста
     */
    private $testResults = [];

    /**
     * Выполняет валидацию формы теста, включая проверку правильности ответов.
     *
     * @param array $post_array Данные POST
     * @return bool true, если данные прошли базовую проверку и ответы корректны, иначе false
     */
    public function validateTest($post_array)
    {
        // Сначала базовая валидация (на пустоту и т.д.) через родительский метод
        if (!parent::validate($post_array)) {
            return false;
        }

        // Теперь специализированная проверка ответов теста
        $q1 = isset($post_array['Q1_Графы']) ? $post_array['Q1_Графы'] : [];
        $q2 = isset($post_array['Q2_Множества']) ? $post_array['Q2_Множества'] : '';
        $q3 = isset($post_array['Q3_Ответ']) ? trim($post_array['Q3_Ответ']) : '';

        // Эталонные ответы
        $correct_q1 = ['Ориентированный граф', 'Неориентированный граф', 'Дерево'];
        $correct_q2 = 'Операция объединения A ∪ B содержит элементы, входящие хотя бы в одно множество';
        $correct_q3 = 'тридцать два';

        // Проверка вопроса 1 (чекбоксы)
        $q1_result = (count(array_diff($correct_q1, $q1)) == 0 && count($q1) == 3) ? 'Верно' : 'Неверно';

        // Проверка вопроса 2 (селект)
        $q2_result = ($q2 == $correct_q2) ? 'Верно' : 'Неверно';

        // Проверка вопроса 3 (текст) – используем вспомогательную функцию my_strtolower
        // (предполагается, что она определена глобально в page_controller.php)
        $normalized_q3 = str_replace(' ', '', my_strtolower($q3));
        $normalized_correct = str_replace(' ', '', $correct_q3);
        $q3_result = ($normalized_q3 == $normalized_correct) ? 'Верно' : 'Неверно';

        // Сохраняем результаты
        $this->testResults = [
            'q1' => $q1_result,
            'q2' => $q2_result,
            'q3' => $q3_result,
        ];

        // Возвращаем true – базовая валидация пройдена, ответы проверены
        return true;
    }

    /**
     * Возвращает результаты проверки ответов теста.
     *
     * @return array
     */
    public function getTestResults()
    {
        return $this->testResults;
    }
}