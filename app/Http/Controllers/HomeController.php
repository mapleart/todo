<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use function Symfony\Component\String\b;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('vue');
    }

    /**
     * Профиль
     * @return array
     */
    public function vueProfile(){
        return [
            'endTasks'=>Task::where('assigned_id', Auth::user()->id)->whereIn('status', array(Task::STATUS_SUCCESS, Task::STATUS_ERROR))->get(),
            'user'=>Auth::user(),
            'counters'=>[
                'hot'=>Task::where('assigned_id', Auth::user()->id)->whereBetween('date_end', array(date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')))->count(),
                'active'=>Task::where('assigned_id', Auth::user()->id)->whereIn('status', array(Task::STATUS_NEW, Task::STATUS_PROCESS))->count(),
                'end'=>Task::where('assigned_id', Auth::user()->id)->whereIn('status', array(Task::STATUS_SUCCESS, Task::STATUS_ERROR))->count(),
                'staffs'=>User::where('parent_id', Auth::user()->id)->count()
            ],
            'success'=>1
        ];
    }

    public function vueHome(){
        return [
            'success'=>true
        ];
    }
    public function vueHomeStaff(){
        return [
            'success'=>true,
            'staffs'=> User::where('parent_id', Auth::user()->id)->get()
        ];
    }

    /**
     * Страница создания
     * @return array
     */
    public function vueCreateTask(){
        return [
            'success'=>true,
            'staffs'=> User::where('parent_id', Auth::user()->id)->get()
        ];
    }

    /**
     * Сохраняет задачу
     * @param Request $request
     * @return array
     */
    public function vueCreateTaskSubmit(Request $request){
        $assignedId = Auth::user()->id;
        // Проверяем ответвеного
        if( Auth::user()->role == 'head') {
            if($assignedUser = User::where('id', $request->get('assigned_id'))->first()) {
                if($assignedUser->parent_id != Auth::user()->id) {
                    return [
                        'success'=>false,
                        'message'=>'Пользователь не ваш подчиненный'
                    ];
                }

                $assignedId = $assignedUser->id;
            }
        }

        $endDate = date('Y-m-d 23:59:59', strtotime($request->get('date_end')));
        if($endDate < date('Y-m-d 23:59:59')) {
            return [
                'success'=>false,
                'message'=>'Укажите дату завершении позже текущей'
            ];
        }

        $task = new Task();
        $task->user_id = Auth::user()->id;
        $task->assigned_id = $assignedId;
        $task->title = $request->get('title');
        $task->description = $request->get('description');
        $task->status = Task::STATUS_NEW;
        $task->priority = (int)$request->get('priority');
        $task->date_end = $endDate;

        $task->save();
        return [
            'success'=>true,
            'message'=>'Задача добавлена',
            'task'=> $task
        ];
    }
    /**
     * Сохранение профиля
     * @return array
     */
    public function vueSaveProfile(Request $request){
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'surname' => 'required',
            'middle_name' => 'required',
        ], [
            'name.required'=>'Обязательное поле',
            'surname.required'=>'Обязательное поле',
            'middle_name.required'=>'Обязательное поле',
        ]);

        $user->name = $request->get('name');
        $user->surname = $request->get('surname');
        $user->middle_name = $request->get('middle_name');

        $user->save();

        $errors = $validator->errors()->toArray();
        return [
            'formErrors'=>$errors,
            'user'=>$user,
            'success'=>1
        ];
    }

    /**
     * Фильт заданий
     * @param Request $request
     */
    public function vueFetchTask(Request $request){
        $userCurrentId = Auth::user()->id;
        $page = $request->get('page', 1);
        $filterDate = $request->get('filterDate', 'all');
        $filterUser = $request->get('filterUser', 'all');

        $tasks = Task::query();

        // Собираем фильтр по дате
        $baseOrder = 'date_end';
        $baseOrderDir = 'desc';
        switch ($filterDate) {
            case 'today':
                $tasks->whereBetween('date_end', array(date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')));
                $baseOrderDir = 'asc';
                break;
            case 'week':
                $startDate = date('Y-m-d 00:00:00');
                $endDate = strtotime($startDate);
                $endDate = strtotime("+7 day", $endDate);
                $tasks->whereBetween('date_end', array(date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59', $endDate)));
                $baseOrderDir = 'asc';
                break;
            case 'all':
            default:
                $baseOrder = 'updated_at';

        }

        // Собираем фильтр по пользователям
        $userIds = [];
        if(Auth::user()->role == 'head' && $request->get('pageType') !== 'my') {
            if($filterUser == 'all') {
                $staffIds = User::where('parent_id', $userCurrentId)->pluck('id')->toArray();
                $userIds = array_merge($userIds, $staffIds);

            } elseif ($filterUser and is_numeric($filterUser) and $userFilter = User::where('id', $filterUser)->where('parent_id', $userCurrentId)->first()) {
                $userIds[]=$userFilter->id;
            }
        } else {
            $userIds[] = $userCurrentId;
        }



        $userIds = array_unique($userIds);
        $tasks->whereIn('assigned_id', $userIds);
        $tasks->orderBy($baseOrder, $baseOrderDir);
        $tasks->orderBy('priority', 'desc');
        $tasks->paginate(30);

        return [
            'order'=>$baseOrder,
            'tasks'=>$tasks->get(),
            'success'=>1,
        ];
    }

    /**
     *
     * @param Request $request
     */
    public function vueTaskStatus(Request $request){
        $user = Auth::user();

        if(!$task = Task::where('id', $request->get('taskId'))->first()) {
            return [
                'success'=>0,
                'message'=>'Задача не найдена'
            ];
        }
        if(!$task->checkEditStatus()) {
            return [
                'success'=>0,
                'message'=>'Нет доступа'
            ];
        }
        $newStatus = $request->get('status');
        if(!in_array($newStatus, [Task::STATUS_NEW, Task::STATUS_PROCESS, Task::STATUS_SUCCESS, Task::STATUS_ERROR])) {
            return [
                'success'=>0,
                'message'=>'Нет такого статуса'
            ];
        }
        $task->status = $newStatus;
        $task->update();
        return [
            'success'=>true,
            'message'=>'Статус изменен',
            'task'=> $task
        ];
    }

    /*
     * Просмотр задачи на странице
     * @param Request $request
     */
    public function vueTaskView(Request $request){
        $user = Auth::user();

        if(!$task = Task::where('id', $request->get('taskId'))->first()) {
            return [
                'success'=>0,
                'message'=>'Задача не найдена'
            ];
        }
        if(!$task->checkView()) {
            return [
                'success'=>0,
                'message'=>'Нет доступа'
            ];
        }

        return [
            'success'=>true,
            'task'=> $task
        ];
    }
    /*
     * Просмотр задачи на странице
     * @param Request $request
     */
    public function vueTaskEditData(Request $request){
        $user = Auth::user();

        if(!$task = Task::where('id', $request->get('taskId'))->first()) {
            return [
                'success'=>0,
                'message'=>'Задача не найдена'
            ];
        }
        if(!$task->checkEditStatus()) {
            return [
                'success'=>0,
                'message'=>'Нет доступа'
            ];
        }

        return [
            'success'=>true,
            'task'=> $task,
            'staffs'=> User::where('parent_id', Auth::user()->id)->get()
        ];
    }
}
