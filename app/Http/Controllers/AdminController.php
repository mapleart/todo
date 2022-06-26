<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

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


    public function getUsersTree(){
        $usersRoot = User::where('parent_id', 0)->get();
        $flatTree = [];
        foreach ($usersRoot as $user) {
            $flatTree[] = $user;
            foreach ($user->childs()->get() as $userSub) {
                $flatTree[] = $userSub;
            }
        }

        return $flatTree;
    }

    public function vueAdmin(){

        return [
            'users'=>$this->getUsersTree(),
            'heads'=> User::where('parent_id', 0)->where('role', 'head')->get(),
            'success'=>1
        ];
    }

    /**
     * Сменя роли у пользователя
     * @param Request $request
     * @return array
     */
    public function vueAdminSaveUserRole(Request $request){
        if(!$user = User::where('id', $request->get('userId'))->first()) {
            return [
                'success'=>0,
                'message'=>'Пользователь не найден'
            ];
        }
        $role = $request->get('role');
        if(!in_array($role, ['staff', 'head'])) {
            return [
                'success'=>0,
                'message'=>'Роль не верная'
            ];
        }

        if($role == 'head') {
            // пользоватлеь переходит в руководители
            $user->parent_id=0;
            $user->role = $role;
            $user->update();
        } else {
            User::where('parent_id', $user->id)->update(['parent_id' => 0]);
            $user->parent_id=0;
            $user->role = $role;
            $user->update();
        }


        return [
            'success'=>1,
            'message'=>'Успешно выполнено',
            'users'=>$this->getUsersTree(),
            'heads'=> User::where('parent_id', 0)->where('role', 'head')->get(),
        ];
    }

    /**
     * Сменя руководителя у пользователя
     * @param Request $request
     * @return array
     */
    public function vueAdminSaveUserHead(Request $request){
        if(!$user = User::where('id', $request->get('userId'))->first()) {
            return [
                'success'=>0,
                'message'=>'Пользователь не найден'
            ];
        }
        if(!$userParent = User::where('id', $request->get('headId'))->first()) {
            if( (int)$request->get('headId') == 0) {
                // Удаление
                $user->parent_id = 0;
                $user->update();

                return [
                    'success'=>1,
                    'message'=>'Успешно выполнено',
                    'users'=>$this->getUsersTree(),
                    'heads'=> User::where('parent_id', 0)->where('role', 'head')->get(),
                ];
            }
            return [
                'success'=>0,
                'message'=>'Руководитель  не найден'
            ];
        }
        if($userParent->id == $user->id) {
            return [
                'success'=>0,
                'message'=>'Зачем вы хотите руководить собой?'
            ];
        }
        if($userParent->role != 'head' ) {
            return [
                'success'=>0,
                'message'=>'Руководитель не руководитель )'
            ];
        }

        $user->parent_id = $userParent->id;
        $user->update();

        return [
            'success'=>1,
            'message'=>'Успешно выполнено',
            'users'=>$this->getUsersTree(),
            'heads'=> User::where('parent_id', 0)->where('role', 'head')->get(),
        ];
    }
}
