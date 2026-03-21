<?php

require_once 'app/core/BaseActiveRecord.php';

class TestResultModel extends BaseActiveRecord
{
    protected static $tablename = 'test_results';
    
    public $id;
    public $fio;
    public $group_name;
    public $q1_result;
    public $q2_result;
    public $q3_result;
    public $total_correct;
    public $test_date;
    
    /**
     * Получает общий результат в читаемом формате
     */
    public function getTotalResult()
    {
        return "$this->total_correct / 3";
    }
    
    /**
     * Получает форматированную дату тестирования
     */
    public function getFormattedDate()
    {
        if ($this->test_date) {
            return date('d.m.Y H:i:s', strtotime($this->test_date));
        }
        return '';
    }
}