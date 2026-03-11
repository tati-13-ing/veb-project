<?php

class FormValidation
{
    /**
     * @var array Правила валидации: [ 'field' => [ ['validator' => 'name', 'param' => mixed], ... ] ]
     */
    private $rules = [];

    /**
     * @var array Список текстов ошибок, возникших при последней валидации
     */
    private $errors = [];

    /**
     * Добавляет правило валидации для поля
     *
     * @param string $field_name Имя поля в форме
     * @param string $validator_name Имя валидатора (isNotEmpty, isInteger, isLess, isGreater, isEmail)
     * @param mixed|null $param Параметр для валидаторов isLess / isGreater
     */
    public function setRule($field_name, $validator_name, $param = null)
    {
        $this->rules[$field_name][] = [
            'validator' => $validator_name,
            'param'     => $param
        ];
    }

    /**
     * Выполняет валидацию данных согласно установленным правилам
     *
     * @param array $post_array Массив данных для проверки (обычно $_POST)
     * @return bool true, если ошибок нет, иначе false
     */
    public function validate($post_array)
    {
        $this->errors = []; // сбрасываем предыдущие ошибки

        foreach ($this->rules as $field => $validators) {
            $value = isset($post_array[$field]) ? $post_array[$field] : null;

            foreach ($validators as $rule) {
                $validator = $rule['validator'];
                $param     = $rule['param'];
                $error     = null;

                switch ($validator) {
                    case 'isNotEmpty':
                        $error = $this->checkNotEmpty($field, $value);
                        break;
                    case 'isInteger':
                        $error = $this->checkInteger($field, $value);
                        break;
                    case 'isLess':
                        $error = $this->checkLess($field, $value, $param);
                        break;
                    case 'isGreater':
                        $error = $this->checkGreater($field, $value, $param);
                        break;
                    case 'isEmail':
                        $error = $this->checkEmail($field, $value);
                        break;
                }

                if ($error !== null) {
                    $this->errors[] = $error;
                }
            }
        }

        return empty($this->errors);
    }

    /**
     * Возвращает массив текстов ошибок последней валидации
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Выводит все ошибки в формате HTML
     */
    public function showErrors()
    {
        if (empty($this->errors)) {
            return;
        }
        echo '<div class="form-errors"><ul>';
        foreach ($this->errors as $error) {
            echo '<li>' . htmlspecialchars($error) . '</li>';
        }
        echo '</ul></div>';
    }

    // методы проверок 

    /**
     * Проверка на пустое значение (строка после trim, массив с элементами)
     *
     * @param string $field
     * @param mixed $value
     * @return string|null Сообщение об ошибке или null, если проверка пройдена
     */
    private function checkNotEmpty($field, $value)
    {
        $isEmpty = $value === null ||
                   (is_string($value) && trim($value) === '') ||
                   (is_array($value) && count($value) === 0);
        if ($isEmpty) {
            return "Поле '{$field}' обязательно для заполнения.";
        }
        return null;
    }

    /**
     * Проверка, является ли значение целым числом
     *
     * @param string $field
     * @param mixed $value
     * @return string|null
     */
    private function checkInteger($field, $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_INT)) {
            return "Поле '{$field}' должно быть целым числом.";
        }
        return null;
    }

    /**
     * Проверка, что значение – целое число и оно меньше заданного порога
     *
     * @param string $field
     * @param mixed $value
     * @param int $threshold
     * @return string|null
     */
    private function checkLess($field, $value, $threshold)
    {
        $intError = $this->checkInteger($field, $value);
        if ($intError !== null) {
            return $intError;
        }
        if ((int)$value >= (int)$threshold) {
            return "Поле '{$field}' должно быть меньше {$threshold}.";
        }
        return null;
    }

    /**
     * Проверка, что значение – целое число и оно больше заданного порога
     *
     * @param string $field
     * @param mixed $value
     * @param int $threshold
     * @return string|null
     */
    private function checkGreater($field, $value, $threshold)
    {
        $intError = $this->checkInteger($field, $value);
        if ($intError !== null) {
            return $intError;
        }
        if ((int)$value <= (int)$threshold) {
            return "Поле '{$field}' должно быть больше {$threshold}.";
        }
        return null;
    }

    /**
     * Проверка корректности email-адреса
     *
     * @param string $field
     * @param mixed $value
     * @return string|null
     */
    private function checkEmail($field, $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return "Поле '{$field}' должно содержать корректный email адрес.";
        }
        return null;
    }
}