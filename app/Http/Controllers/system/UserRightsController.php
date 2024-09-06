<?php

namespace App\Http\Controllers\system;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\system\ProjectModulesModel;
use App\Models\system\UserRightsModel;
use App\Models\system\UsersModel;
use App\Http\Controllers\Helpers\MainHelper;

class UserRightsController extends Controller
{
    public $module_details = array('moduleId' => 3, 'moduleName' => 'Rights', 'moduleSlug' => 'rights', 'moduleUrl' => 'http://127.0.0.1:8000/rights');

    public function manageRights($userId)
    {
        if(!MainHelper::checkRight("view", $this->module_details['moduleId'])){
            return redirect()->route('unauthorized');
        }

        $modules = ProjectModulesModel::select('id', 'name')->where('isDelete', 1)->get()->toArray();
        $userRights = UserRightsModel::select('rights', 'id', 'isActive')->where('user_id', $userId)->where('isDelete', 1)->first();
        $user_name = UsersModel::selectRaw("CONCAT(name, ' ', surname) AS full_name")->where('id', $userId)->pluck('full_name')->first();
        $rights = [];
        $data = [];
        if($userRights){
            $rights = json_decode($userRights->rights, true) ;
            $data['rights_id'] = $userRights->id;
            $isActive = [];
            $isActive['isActive'] = $userRights->isActive;
            $isActive['buttonClass'] = $userRights->isActive == 1 ? 'btn btn-danger' : 'btn btn-success';
            $isActive['iconClass'] = $userRights->isActive == 1 ? 'Deactivate' : 'Activate';
            $isActive['mode'] = $userRights->isActive == 1 ? 'deactivate' : 'active';
            $isActive['icon'] = $userRights->isActive == 1 ? 'fas fa-power-off' : 'fas fa-toggle-on';
            $data['isActive'] = $isActive;
        }
        $data = array_merge($data, ['modules' => $modules, 'rights' => $rights, 'userId' => $userId, 'user_name' => $user_name, 'module_details' => $this->module_details]);
        return view('system.users.manageRights', $data);
    }

    public function changeRights(Request $request, $user_id) {

        if(!MainHelper::checkRight("add", $this->module_details['moduleId']) || !MainHelper::checkRight("edit", $this->module_details['moduleId'])){
            session()->flash('toastr_error', 'Unauthorized Access!!!');
            return response()->json(['success' => true]);
        }

        if($user_id) {
            $user_name = UsersModel::selectRaw("CONCAT(name, ' ', surname) AS full_name")->where('id', $user_id)->pluck('full_name')->first();
            if($user_name) {
                $rightsExist = UserRightsModel::where('user_id', $user_id)->where('isDelete', 1)->where('isActive', 1)->first();

                if($rightsExist) {
                    $rightsExist->update([
                        'rights' => $request->rights,
                        'updated_at' => now()
                    ]);
                    session()->flash('toastr_success', 'Rights changed successfully for ' . $user_name);
                } elseif($request->has('rights')) {
                    UserRightsModel::create([
                        'user_name' => $user_name,
                        'user_id' => $user_id,
                        'rights' => $request->rights,
                        'created_by' => 0,
                        'created_at' => now()
                    ]);
                    session()->flash('toastr_success', 'Rights assigned successfully to ' . $user_name);
                }
                $user = UsersModel::find($user_id);
                if ($user) {
                    $user->update(["personal_rights" => 1]);
                } else {
                    session()->flash('toastr_error', 'User not found!');
                }
            } else {
                session()->flash('toastr_error', 'User type not found.');
            }
        } else {
            session()->flash('toastr_error', 'Invalid user!!!');
        }
        return response()->json(['success' => true]);
    }

    public function activeDeactivate(Request $request){

        if(!MainHelper::checkRight("edit", $this->module_details['moduleId'])){
            session()->flash('toastr_error', 'Unauthorized Access!!!');
            return response()->json(['success' => true]);
        }

        if ($request->id && $request->mode) {
            $user = UserRightsModel::find($request->id);
            $isActive = $request->mode == 'active' ? 1 : 0;
            $action = $request->mode == 'active' ? 'Activate' : 'Deactivate';
            if ($user) {
                $user->update(['isActive' => $isActive]);
                session()->flash('toastr_success', 'successfully rights '.$action.' For '.$user->user_name);
            } else {
                session()->flash('toastr_error', 'Rights not found For '.$user->user_name);
            }
        } else {
            session()->flash('toastr_error', 'ID Required!!!');
        }

        return response()->json(['success' => true]);

    }

    public function delete($id){

        if(!MainHelper::checkRight("delete", $this->module_details['moduleId'])){
            session()->flash('toastr_error', 'Unauthorized Access!!!');
            return response()->json(['success' => true]);
        }

        if ($id) {
            $user = UserRightsModel::find($id);
            if ($user) {
                $user->update(['isDelete' => 0]);
                session()->flash('toastr_success', 'successfully rights Delete For '.$user->user_name);
            } else {
                session()->flash('toastr_error', 'Rights not found For '.$user->user_name);
            }
        } else {
            session()->flash('toastr_error', 'ID Required!!!');
        }
        return redirect()->route('users');

    }
}
