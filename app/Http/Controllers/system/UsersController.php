<?php

namespace App\Http\Controllers\system;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\system\UsersModel;
use App\Models\system\UsersTypeModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Helpers\MainHelper;

class UsersController extends Controller
{

    public $module_details = array('moduleId' => 2, 'moduleName' => 'Users', 'moduleSlug' => 'users', 'moduleUrl' => 'http://127.0.0.1:8000/users');

    public function index(){

        // dd(session("authentication.rights"));

        if(!MainHelper::checkRight("view" , $this->module_details['moduleId'])){
            return redirect()->route('unauthorized');
        }

        return view('system.users.index', ['module_details' => $this->module_details]);

    }

    public function lodeTable()
    {

        $data_available = false;
        $filds = ['id', 'user_type', 'user_type', DB::raw('CONCAT(name, " ",surname) AS name'), 'email', 'mobile_number', 'user_name', 'isActive'];
        if(session("authentication.rights.view_type_" . $this->module_details['moduleId']) == 1 || session("authentication.user_type_id") == 1){

            $users = MainHelper::getAllUser(session("authentication.user_id"), $filds);

        }else if(session("authentication.rights.view_type_" . $this->module_details['moduleId']) == 2){

            $users = MainHelper::getChainWishUser(session("authentication.user_id"), $filds);

        }else{

            $users = MainHelper::getPersonalUser(session("authentication.user_id"), $filds);

        }

        $users_types = MainHelper::getLowerUsertype(session("authentication.user_type_id"));
        if($users){

            $data_available = true;
            return response()->json(['data_available' => $data_available, 'users' => $users,'users_types' => $users_types]);
        }else{
            return response()->json(['data_available' => $data_available]);
        }
    }

    public function add(){

        if(!MainHelper::checkRight("add", $this->module_details['moduleId'])){
            return redirect()->route('unauthorized');
        }

        $user_types = MainHelper::get_lowerChain(session("authentication.user_id"));
        return view('system.users.crud', ['user_types' => $user_types, 'mode' => 'add']);

    }

    public function checkUserName(Request $request){

        $existingUserName = UsersModel::where('user_name', $request->input('user_name'));
        if ($request->has('id') && $request->input('id') > 0) {
            $existingUserName = $existingUserName->where('id', '!=', $request->input('id'));
        }
        $existingUserName = $existingUserName->first();
        if($existingUserName){
            return response()->json(['ack' => 1]);
        } else {
            return response()->json(['ack' => 0]);
        }
    }

    public function insert(Request $request)
    {
        // dd($request->file('profile_picture'));

        if(!MainHelper::checkRight("add", $this->module_details['moduleId'])){
                return response()->json('Unauthorized Access!!!');
        }

        $existingMember = UsersModel::where('mobile_number', $request->input('mobile_number'))->where('user_name', $request->input('user_name'))->first();

        if ($existingMember) {
            session()->flash('toastr_error', 'Mobile Number already exists!!!');
            return redirect()->route('users.add');
        } else {
            try {
                $validatedData = $request->validate([
                    'user_type' => 'required',
                    'name' => 'required|string|max:255',
                    'surname' => 'required|string|max:255',
                    'mobile_number' => 'required|string|max:10',
                    'email' => 'required|email|max:50',
                    'address' => 'required|string|max:150',
                    'user_name' => 'required|string',
                    'password' => 'required|string',
                ]);
            } catch (ValidationException $e) {
                $errors = $e->errors();
                $errorMessage = 'Error creating member. Please check the form: <br>';
                foreach ($errors as $error) {
                    $errorMessage .= $error[0] . '<br>';
                }
                session()->flash('toastr_error', $errorMessage);
                return redirect()->back()->withInput();
            }

            $encryptedPassword = Hash::make($request->input('password'));
            $upChain = MainHelper::upChain(session('authentication.user_id'));

            $newMember = UsersModel::create([
                'user_type' => $validatedData['user_type'],
                'name' => $validatedData['name'],
                'surname' => $validatedData['surname'],
                'mobile_number' => $validatedData['mobile_number'],
                'email' => $validatedData['email'],
                'address' => $validatedData['address'],
                'user_name' => $validatedData['user_name'],
                'password' => $encryptedPassword,
                'profile_picture' => '',
                'createdBy' => session('authentication.user_id'),
                'uper_chain_ids' => $upChain['uper_chain_ids'],
                'uper_chain' => $upChain['uper_chain'],
            ]);

            $file_name = MainHelper::upload_file('profile_picture', $newMember->id, $request->file('profile_picture'));

            if($file_name){
                $member = UsersModel::find($newMember->id);
                $member->update(['profile_picture' => $file_name]);
            }
            session()->flash('toastr_success', 'Member successfully created!');
            return redirect()->route('users');
        }
    }

    public function edit($id){

        if(!MainHelper::checkRight("edit", $this->module_details['moduleId'])){
            return redirect()->route('unauthorized');
        }

        $user = UsersModel::find($id, ['id', 'user_type', 'name', 'surname', 'mobile_number', 'email', 'address', 'user_name', 'profile_picture']);
        $profile_picture = MainHelper::getFilePath('profile_picture', $id.'/'.$user->profile_picture);
        $user_types = UsersTypeModel::select('id', 'name')->where('isDelete', 1)->where('isActive', 1)->get()->toArray();

        if($user){

            $user = $user->toArray();
            $user['profile_picture'] = $profile_picture;
            return view('system.users.crud', ['user' => $user, 'user_types' => $user_types, 'mode' => 'edit']);

        }else{
            session()->flash('toastr_error', 'User Not Found!!!');
            return redirect()->route('users');
        }
    }

    public function update(Request $request, $id){

        if(!MainHelper::checkRight("edit", $this->module_details['moduleId'])){
            return response()->json('Unauthorized Access!!!');
        }

        if ($id) {
            try {
                $validatedData = $request->validate([
                    'user_type' => 'required',
                    'name' => 'required|string|max:255',
                    'surname' => 'required|string|max:255',
                    'mobile_number' => 'required|string|max:10',
                    'email' => 'required|email|max:50',
                    'address' => 'required|string|max:150',
                    'user_name' => 'required|string'
                ]);
            } catch (ValidationException $e) {
                session()->flash('toastr_error', 'Error updating User. Please check the form.');
                return redirect()->back()->withErrors($e->errors())->withInput();
            }

            $existinguser = UsersModel::where('mobile_number', $request->input('mobile_number'))->where('user_name', $request->input('user_name'))->where('id','!=',$id)->first();
            if ($existinguser) {
                session()->flash('toastr_error', 'User Name already exists!!!');
                return redirect()->route('users.edit', $id)->withInput();
            }

            $user = UsersModel::find($id);
            if ($user) {

                $data = [
                    'user_type' => $validatedData['user_type'],
                    'name' => $validatedData['name'],
                    'surname' => $validatedData['surname'],
                    'mobile_number' => $validatedData['mobile_number'],
                    'email' => $validatedData['email'],
                    'address' => $validatedData['address'],
                    'user_name' => $validatedData['user_name'],
                    'updated_at' => now()
                ];

                if($request->file('profile_picture')){
                    $file_name = MainHelper::upload_file('profile_picture', $id, $request->file('profile_picture'));
                    $data['profile_picture'] = $file_name;
                }

                $user->update($data);

                session()->flash('toastr_success', 'User successfully updated!');
                return redirect()->route('users');
            } else {
                session()->flash('toastr_error', 'User not found!');
                return redirect()->route('users');
            }
        } else {
            session()->flash('toastr_error', 'ID Required!!!');
            return redirect()->route('users');
        }

    }

    public function delete($id){

        if(!MainHelper::checkRight("delete", $this->module_details['moduleId'])){
            return response()->json(['ack' => 0, 'Message' => 'Unauthorized Access!!!']);
        }

        if ($id) {
            $user = UsersModel::find($id);
            if ($user) {
                $user->update(['isDelete' => 0]);
                $ack = ['ack' => 1, 'Message' => 'User successfully deleted!'];
            } else {
                $ack = ['ack' => 0, 'Message' => 'User not found'];
            }
        } else {
            $ack = ['ack' => 0, 'Message' => 'ID Required!!!'];
        }

        return response()->json($ack);

    }

    public function activeDeactivate(Request $request){

        if(!MainHelper::checkRight("edit", $this->module_details['moduleId'])){
            return response()->json(['ack' => 0, 'Message' => 'Unauthorized Access!!!']);
        }

        if ($request->id && $request->mode) {
            $user = UsersModel::find($request->id);
            $isActive = $request->mode == 'active' ? 1 : 0;
            if ($user) {
                $user->update(['isActive' => $isActive]);
                $ack = ['ack' => 1, 'Message' => 'User successfully '.$request->mode];
            } else {
                $ack = ['ack' => 0, 'Message' => 'User not found'];
            }
        } else {
            $ack = ['ack' => 0, 'Message' => 'ID Required!!!'];
        }

        return response()->json($ack);

    }

    public function editProfile($id)
    {

        $user = UsersModel::find($id, ['id', 'user_type', 'name', 'surname', 'mobile_number', 'email', 'address', 'user_name', 'profile_picture']);
        $profile_picture = MainHelper::getFilePath('profile_picture', $id.'/'.$user->profile_picture);

        if ($user) {
            $user = $user->toArray();
            $user_type = UsersTypeModel::selectRaw("name")->where('id', $user['id'])->pluck('name')->first();
            $user['profile_picture'] = $profile_picture;
            $user['user_type'] = $user_type;
            $data = ['user' => $user];
            return response()->json($data);
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    public function updateProfile(Request $request, $id){

        if ($id) {
            try {
                $validatedData = $request->validate([
                    'user_name' => 'required|string',
                    'name' => 'required|string|max:255',
                    'surname' => 'required|string|max:255',
                    'mobile_number' => 'required|string|max:10',
                    'email' => 'required|max:50',
                    'address' => 'required|string|max:150'
                ]);

                $existinguser = UsersModel::where('mobile_number', $request->input('mobile_number'))->where('user_name', $request->input('user_name'))->where('id','!=',$id)->first();
                if ($existinguser) {
                    $ack = ['ack' => 0, 'Message' => 'User Name already exists!!!'];
                    return response()->json($ack);
                }

                $user = UsersModel::find($id);
                if ($user) {

                    $data = [
                        'name' => $validatedData['name'],
                        'surname' => $validatedData['surname'],
                        'mobile_number' => $validatedData['mobile_number'],
                        'email' => $validatedData['email'],
                        'address' => $validatedData['address'],
                        'user_name' => $validatedData['user_name'],
                        'updated_at' => now()
                    ];
                    if($request->file('profile_picture')){
                        $file_name = MainHelper::upload_file('profile_picture', $id, $request->file('profile_picture'));
                        $profile_picture = MainHelper::getFilePath('profile_picture', $id.'/'.$file_name);
                        $data['profile_picture'] = $file_name;
                        session()->put('authentication.profile_picture', $profile_picture);
                    }

                    $user->update($data);

                    session()->put('authentication.user_name', $validatedData['name']." ".$validatedData['surname']);
                    session()->put('authentication.email', $validatedData['email']);

                    $ack = ['ack' => 1, 'Message' => 'Profile successfully updated!!!'];
                } else {
                    $ack = ['ack' => 0, 'Message' => 'User not found!!!'];
                }
            } catch (ValidationException $e) {
                $ack = ['ack' => 0, 'Message' => 'Error updating Profile. Please check the form.'];
            }
        } else {
            $ack = ['ack' => 0, 'Message' => 'ID Required!!!'];
        }
        return response()->json($ack);
    }

}
