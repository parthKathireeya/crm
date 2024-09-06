<?php

namespace App\Http\Controllers\system;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\system\ProjectModulesModel;
use App\Models\system\ProjectModuleTypeModel;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Helpers\MainHelper;

class ProjectModulesController extends Controller
{

    public $module_details = array('moduleId' => 1, 'moduleName' => 'System Modules', 'moduleSlug' => 'system_modules', 'moduleUrl' => 'http://127.0.0.1:8000/modules');

    public function index(){

        if(!MainHelper::checkRight("view" , $this->module_details['moduleId'])){
            return redirect()->route('unauthorized');
        }

        $modules = ProjectModulesModel::all()->where('isDelete', 1)->where('isActive', 1);
        $data = $modules->map(function ($module) {
            $moduleData = $module->toArray();
            return $moduleData;
        })->toArray();

        return view('system.projectModules.index', ['modules' => $data, 'module_details' => $this->module_details]);

    }

    public function lodeTable()
    {

        $data = [];
        $data_available = false;
        $users_types = MainHelper::getLowerUsertype(session("authentication.user_type_id"));
        $modules = ProjectModulesModel::query()->select(['id','name','slug','url','show_no'])->where('isDelete', 1)->where('isActive', 1)->get();
        if($modules->count() > 0){

            $data_available = true;
            $data = $modules->toArray();
        }
        return response()->json(['success' => true, 'data' => $data, 'data_available' => $data_available,'users_types' => $users_types]);
    }

    public function add(){

        if(!MainHelper::checkRight("add", $this->module_details['moduleId'])){
            return redirect()->route('unauthorized');
        }

        $project_module_type = ProjectModuleTypeModel::select('name', 'id')->where('isDelete', 1)->where('isActive', 1)->get()->toArray();
        return view('system.projectModules.crud', ['project_module_type' => $project_module_type, 'mode' => 'add']);

    }

    public function insert(Request $request){

        if(!MainHelper::checkRight("add", $this->module_details['moduleId'])){
            return response()->json(['ack' => 0, 'Message' => 'Unauthorized Access!!!']);
        }

        $existingModule = ProjectModulesModel::where('slug', $request->input('slug'))->where('url', $request->input('url'))->first();

        if ($existingModule) {
            session()->flash('toastr_error', 'Module already exists!!!');
            return redirect()->route('modules.create');
        } else {
            try {
                $validatedData = $request->validate([
                    'type' => 'required',
                    'slug' => 'required|string|max:45',
                    'icon_class' => 'required|string|max:45',
                    'name' => 'required|string|max:45',
                    'url' => 'required|max:190',
                    'description' => 'max:240'
                ]);
            } catch (ValidationException $e) {
                session()->flash('toastr_error', 'Error creating Module. Please check the form.');

                return redirect()->back()->withErrors($e->errors())->withInput();
            }
            // dd($validatedData);
            $slug = str_replace(' ', '', $validatedData['slug']);
            $isShow = $request->isShow != null ? 1 : 0;
            ProjectModulesModel::create([
                'type' => $validatedData['type'],
                'name' => $validatedData['name'],
                'icon_class' => $validatedData['icon_class'],
                'slug' => $slug,
                'url' => $validatedData['url'],
                'description' => $validatedData['description'],
                'isShow' => $isShow
            ]);

            session()->flash('toastr_success', 'Module successfully created!');
            return redirect()->route('modules');
        }

    }

    public function edit($id){

        if(!MainHelper::checkRight("edit", $this->module_details['moduleId'])){
            return redirect()->route('unauthorized');
        }

        $module = ProjectModulesModel::find($id);
        if($module){
            $module = $module->toArray();
            $project_module_type = ProjectModuleTypeModel::select('name', 'id')->where('isDelete', 1)->where('isActive', 1)->get()->toArray();
            return view('system.projectModules.crud', ['module' => $module, 'project_module_type' => $project_module_type, 'mode' => 'edit']);
        }else{
            return redirect()->route('modules');
        }

    }

    public function update(Request $request, $id)
    {

        if(!MainHelper::checkRight("edit", $this->module_details['moduleId'])){
            return response()->json(['ack' => 0, 'Message' => 'Unauthorized Access!!!']);
        }

        if ($id) {
            try {
                $validatedData = $request->validate([
                    'type' => 'required',
                    'name' => 'required|string|max:45',
                    'icon_class' => 'required|string|max:45',
                    'slug' => 'required|string|max:45',
                    'url' => 'required|max:190',
                    'description' => 'max:240'
                ]);
            } catch (ValidationException $e) {
                session()->flash('toastr_error', 'Error updating Module. Please check the form.');
                return redirect()->back()->withErrors($e->errors())->withInput();
            }

            $existingModule = ProjectModulesModel::where('slug',$validatedData['slug'])->where('url', $validatedData['url'])->where('id', "!=", $id)->first();
            if ($existingModule) {
                session()->flash('toastr_error', 'Module already exists!!!');
                return redirect()->route('modules.edit', $id)->withInput();
            }

            $module = ProjectModulesModel::find($id);
            $newSlug = str_replace(' ', '', $validatedData['slug']);
            $isShow = $request->isShow != null ? 1 : 0;
            if ($module) {
                $module->update([
                    'type' => $validatedData['type'],
                    'name' => $validatedData['name'],
                    'icon_class' => $validatedData['icon_class'],
                    'slug' => $newSlug,
                    'url' => $validatedData['url'],
                    'description' => $validatedData['description'],
                    'isShow' => $isShow,
                    'updated_at' => now()
                ]);

                session()->flash('toastr_success', 'Module successfully updated!');
                return redirect()->route('modules');
            } else {
                session()->flash('toastr_error', 'Module not found!');
                return redirect()->route('modules');
            }
        } else {
            session()->flash('toastr_error', 'ID Required!!!');
            return redirect()->route('modules');
        }
    }

    public function change_showNo(Request $request) {

        if(!MainHelper::checkRight("edit", $this->module_details['moduleId'])){
            return response()->json(['ack' => 0, 'Message' => 'Unauthorized Access!!!']);
        }

        if ($request->id && $request->show_no) {
            $module = ProjectModulesModel::find($request->id);
            if ($module) {
                $module->update(['show_no' => $request->show_no]);
                $ack = ['ack' => 1, 'Message' => 'Show No successfully updated!'];
            } else {
                $ack = ['ack' => 0, 'Message' => 'Module not found'];
            }
        } else {
            $ack = ['ack' => 0, 'Message' => 'Required data missing'];
        }
        return response()->json($ack);
    }

    public function delete($id)
    {

        if(!MainHelper::checkRight("delete", $this->module_details['moduleId'])){
            return response()->json(['ack' => 0, 'Message' => 'Unauthorized Access!!!']);
        }

        if ($id) {
            $module = ProjectModulesModel::find($id);
            if ($module) {
                $module->update(['isDelete' => 0]);
                $ack = ['ack' => 1, 'Message' => 'Module successfully deleted!'];
            } else {
                $ack = ['ack' => 0, 'Message' => 'Module not found'];
            }
        } else {
            $ack = ['ack' => 0, 'Message' => 'ID Required!!!'];
        }

        return response()->json($ack);
    }
}
