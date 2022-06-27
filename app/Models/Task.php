<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    const STATUS_NEW = 0;
    const STATUS_PROCESS = 1;
    const STATUS_SUCCESS = 2;
    const STATUS_ERROR = 5;

    const PRIORITY_LOW = 0;
    const PRIORITY_MIDDLE = 1;
    const PRIORITY_HIGH = 2;


    use HasFactory;


    /**
     * Проверяет возможность редактирования (полного)
     */
    public function checkEdit(){
        return $this->user_id == Auth::user()->id;
    }
    /**
     * Проверяет возможность смены статуса
     */
    public function checkEditStatus(){
        return $this->checkEdit() || $this->assigned_id == Auth::user()->id;
    }
    /**
     * Проверяет возможность просмотра
     */
    public function checkView(){
        return $this->checkEditStatus();
    }


    /**
     * Возвращает кодовое название приоретета
     * @return string
     */
    public function getPriorityCode(){
        $schema = [
            self::PRIORITY_LOW => 'low',
            self::PRIORITY_MIDDLE => 'middle',
            self::PRIORITY_HIGH => 'high',
        ];
        return $schema[ $this->priority ] ?? 'low';
    }

    /**
     * Выводит название приоретета
     * @return string
     */
    public function getPriorityText(){
        $schema = [
            self::PRIORITY_LOW => 'Низкий',
            self::PRIORITY_MIDDLE => 'Средний',
            self::PRIORITY_HIGH => 'Высокий',
        ];
        return $schema[ $this->priority ] ?? 'Низкий';
    }

    /**
     * Возвращает текст статуса
     * @return string
     */
    public function getStatusText() {
        $schema = [
            self::STATUS_NEW=>'К выполнению',
            self::STATUS_PROCESS=>'Выполняется',
            self::STATUS_SUCCESS=>'Выполнена',
            self::STATUS_ERROR=>'Отменена',
        ];

        return $schema[$this->status] ?? 'К выполнению';
    }

    /**
     * Код статуса
     * @return string
     */
    public function getStatusCode() {
        $schema = [
            self::STATUS_NEW=>'new',
            self::STATUS_PROCESS=>'process',
            self::STATUS_SUCCESS=>'success',
            self::STATUS_ERROR=>'error',
        ];

        return $schema[$this->status] ?? 'new';
    }
    public function toArray()
    {
        $data = parent::toArray();
        $endDate = date('Y-m-d', strtotime($this->date_end));
        $data['allow_edit'] = (int)$this->checkEdit();
        $data['allow_edit_status'] = (int)$this->checkEditStatus();

        $data['time_add']=strtotime($this->created_at);
        $data['time_update']=strtotime($this->updated_at);
        $data['time_end']=strtotime($this->date_end);
        $data['date_end']= $endDate == date('Y-m-d') ? 'Сегодня' : $endDate;

        $data['status_code'] = $this->getStatusCode();
        $data['status_text'] = $this->getStatusText();
        $data['priority_code'] = $this->getPriorityCode();
        $data['priority_text'] = $this->getPriorityText();
        $data['user'] = User::where('id', $this->user_id)->first();
        $data['assigned'] = User::where('id', $this->assigned_id)->first();
        return $data;
    }
}
