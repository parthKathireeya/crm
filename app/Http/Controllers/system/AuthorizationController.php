<?php

namespace App\Http\Controllers\system;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use App\Models\system\UsersModel;
use App\Http\Controllers\Helpers\MainHelper;
use Illuminate\Support\Facades\Session;

class AuthorizationController extends Controller
{

    public function login(){
        if(!session("authentication.login")){
            return view('system.login');
        }else{
            return redirect()->route('users');
        }
    }

    public function do_login(Request $request)
    {

        // dd($request->password);

        try {

            $request->validate([
                'user_name' => 'required|max:50',
                'password' => 'required',
            ]);

        } catch (ValidationException $e) {

            session()->flash('toastr_error', 'invalid username password!!!');
            return redirect()->back()->withErrors($e->errors())->withInput();

        }

        $user = UsersModel::where('user_name', $request->user_name)->first();
        if ($user){
            if(password_verify($request->password, $user->password)) {
                if($user->personal_rights == 1){
                    $adminDetails = MainHelper::get_adminDetails($user->user_type, $user->id, true);
                }else{
                    $adminDetails = MainHelper::get_adminDetails($user->user_type);
                }

                $profile_picture = MainHelper::getFilePath('profile_picture', $user->id.'/'.$user->profile_picture);

                $authentication = [];
                $authentication['login'] = true;
                $authentication['user_type'] = $adminDetails['name'];
                $authentication['user_type_id'] = $user->user_type;
                $authentication['user_id'] = $user->id;
                $authentication['user_name'] = $user->name ." ". $user->surname;
                $authentication['email'] = $user->email;
                $authentication['profile_picture'] = $profile_picture;
                $authentication['rights'] = $adminDetails['rights'];
                session()->put('authentication', $authentication);
                session()->flash('toastr_success', 'Login successful!');
                return redirect()->route('dashboard');
            }else{
                session()->flash('toastr_error', 'Invalid Password!');
                return redirect()->route('login');
            }
        }else{
            session()->flash('toastr_error', 'User not found!');
            return redirect()->route('login');
        }

    }

    public function logout()
    {
        Session::forget('authentication');
        session()->flash('toastr_success', 'Logout successful!');

        return redirect()->route('login');
    }

}
