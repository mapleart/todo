<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    const STATUS_NEW = 0;
    const STATUS_PROCESS = 1;
    const STATUS_SUCCESS = 2;
    const STATUS_ERROR = 5;
    use HasFactory;


    public function getStatusText() {
        $schema = [
            self::STATUS_NEW=>'К выполнению',
            self::STATUS_PROCESS=>'Выполняется',
            self::STATUS_SUCCESS=>'Выполнена',
            self::STATUS_ERROR=>'Отменена',
        ];

        return $schema[$this->status] ?? 'К выполнению';
    }
    public function toArray()
    {
        $data = parent::toArray();
        $data['status_text'] =$this->getStatusText();
        return $data;
    }
}
