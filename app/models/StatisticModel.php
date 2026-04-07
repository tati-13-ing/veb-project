<?php
require_once 'app/core/BaseActiveRecord.php';

class StatisticModel extends BaseActiveRecord
{
    protected static $tablename = 'statistics';
    
    public $id;
    public $visit_datetime;
    public $page_url;
    public $ip_address;
    public $host_name;
    public $browser_name;
    
    /**
     * Сохраняет текущую запись статистики
     */
    public function save()
    {
        if (empty($this->visit_datetime)) {
            $this->visit_datetime = date('Y-m-d H:i:s');
        }
        return parent::save();
    }
    
    /**
     * Получает форматированную дату/время
     */
    public function getFormattedDateTime()
    {
        if ($this->visit_datetime) {
            return date('d.m.Y H:i:s', strtotime($this->visit_datetime));
        }
        return '';
    }
}