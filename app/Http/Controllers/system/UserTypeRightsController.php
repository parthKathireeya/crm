<?php

namespace App\Http\Controllers\system;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\system\UsersTypeModel;
use App\Models\system\ProjectModulesModel;
use App\Models\system\UsertypeRightsModel;
use GuzzleHttp\Psr7\Message;
use App\Http\Controllers\Helpers\MainHelper;

class UserTypeRightsController extends Controller
{
    public $module_details = array('moduleId' => 3, 'moduleName' => 'Rights', 'moduleSlug' => 'rights', 'moduleUrl' => 'http://127.0.0.1:8000/rights');

    function index(){


        if(!MainHelper::checkRight("view" , $this->module_details['moduleId'])){
            return redirect()->route('unauthorized');
        }

        return view('system.userTypeRights.index', ['module_details' => $this->module_details]);

    }

    public function lodeTable()
    {
        $data_available = false;
        $UsersTypes =UsersTypeModel::select('id', 'name')->where('isDelete', 1)->where('id', '>', session('authentication.user_type_id'))->get()->toArray();
        $array = [];

        for ($i=0; $i < sizeof($UsersTypes); $i++) {

            $right_data = UsertypeRightsModel::select('id','isDelete','isActive')->where('user_type_id', $UsersTypes[$i]['id'])->where('isDelete', 1)->first();

            $UsersTypes[$i]['right_id'] = $right_data ? $right_data->id : 0;
            $UsersTypes[$i]['isActive'] = $right_data ? $right_data->isActive : 0;

        }
        if($UsersTypes){

            $data_available = true;
            return response()->json(['data_available' => $data_available, 'UsersTypes' => $UsersTypes]);
        }else{
            return response()->json(['data_available' => $data_available]);
        }
    }

    public function manageRights($userTypeId)
    {

        if(!MainHelper::checkRight("view", $this->module_details['moduleId']) || !MainHelper::checkRight("add", $this->module_details['moduleId']) || !MainHelper::checkRight("edit", $this->module_details['moduleId'])){
            return redirect()->route('unauthorized');
        }

        $modules = ProjectModulesModel::select('id', 'name')->where('isDelete', 1)->get()->toArray();
        $rights = UsertypeRightsModel::select('rights')->where('user_type_id', $userTypeId)->where('isDelete', 1)->value('rights');
        $userType = UsersTypeModel::selectRaw("name")->where('id', $userTypeId)->pluck('name')->first();
        $rights = $rights ? json_decode($rights, true) : [];
        return view('system.userTypeRights.manageRights', ['modules' => $modules, 'rights' => $rights, 'userTypeId' => $userTypeId, 'userType' => $userType, 'module_details' => $this->module_details]);
    }


    public function changeRights(Request $request, $userTypeId) {
        if(!MainHelper::checkRight("add", $this->module_details['moduleId']) || !MainHelper::checkRight("edit", $this->module_details['moduleId'])){
            return response()->json(['ack' => 0, 'Message' => 'Unauthorized Access!!!']);
        }
        if($userTypeId) {
            $userType = UsersTypeModel::select('name')->where('id', $userTypeId)->pluck('name')->first();
            if($userType) {
                $rightsExist = UsertypeRightsModel::where('user_type_id', $userTypeId)->where('isDelete', 1)->where('isActive', 1)->first();

                if($rightsExist) {
                    $rightsExist->update([
                        'rights' => $request->rights,
                        'updated_at' => now()
                    ]);
                    return response()->json(['ack' => 1, 'Message' => 'Rights changed successfully for ' . $userType]);
                } elseif($request->has('rights')) {
                    UsertypeRightsModel::create([
                        'user_type' => $userType,
                        'user_type_id' => $userTypeId,
                        'rights' => $request->rights,
                        'created_by' => 0,
                        'created_at' => now()
                    ]);
                    return response()->json(['ack' => 1, 'Message' => 'Rights assigned successfully to ' . $userType]);
                } else {
                    return response()->json(['ack' => 0, 'Message' => 'No rights provided.']);
                }
            } else {
                return response()->json(['ack' => 0, 'Message' => 'User type not found.']);
            }
        } else {
            return response()->json(['ack' => 0, 'Message' => 'Invalid user!!!']);
        }
    }

    public function activeDeactivate(Request $request){
        if(!MainHelper::checkRight("add", $this->module_details['moduleId']) || !MainHelper::checkRight("edit", $this->module_details['moduleId'])){
            return response()->json(['ack' => 0, 'Message' => 'Unauthorized Access!!!']);
        }
        if ($request->id && $request->mode) {
            $user = UsertypeRightsModel::where('isDelete', 1)->where('user_type_id', $request->id)->first();
            $isActive = $request->mode == 'active' ? 1 : 0;
            $action = $request->mode == 'active' ? 'Activated' : 'Deactivated';
            if ($user) {
                $user->update(['isActive' => $isActive]);
                $ack = ['ack' => 1, 'Message' => 'Successfully rights '.$action.' for '.$user->user_type];
            } else {
                $ack = ['ack' => 0, 'Message' => 'Rights not found for specified user type'];
            }
        } else {
            $ack = ['ack' => 0, 'Message' => 'ID and mode are required'];
        }

        return response()->json($ack);
    }

    public function delete($id){
        if(!MainHelper::checkRight("delete", $this->module_details['moduleId']) || !MainHelper::checkRight("edit", $this->module_details['moduleId'])){
            return response()->json(['ack' => 0, 'Message' => 'Unauthorized Access!!!']);
        }

        if ($id) {
            $user = UsertypeRightsModel::where('isDelete', 1)->where('user_type_id', $id)->first();
            if ($user) {
                $user->update(['isDelete' => 0]);
                $ack = ['ack' => 1, 'Message' => 'successfully rights Delete For '.$user->user_type];
            } else {
                $ack = ['ack' => 0, 'Message' => 'Rights not found For '.$user->user_type];
            }
        } else {
            $ack = ['ack' => 0, 'Message' => 'ID Required!!!'];
        }

        return response()->json($ack);

    }

}
