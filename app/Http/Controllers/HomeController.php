<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
            'user'=>Auth::user(),
            'counters'=>[
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

    public function vueCreateTask(){
        return [
            'success'=>true,
            'staffs'=> User::where('parent_id', Auth::user()->id)->get()
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
}
