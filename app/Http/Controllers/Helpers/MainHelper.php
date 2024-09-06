<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\system\UsersTypeModel;
use App\Models\system\UsertypeRightsModel;
use App\Models\system\UserRightsModel;
use App\Models\system\UsersModel;

class MainHelper extends Controller
{
    public static function get_adminDetails($user_type, $user_id = 0, $personalAccess = false) {

        $admin_details = UsersTypeModel::find($user_type);
        if ($personalAccess == true) {
            $rights = UserRightsModel::where('user_id', $user_id)->where('isActive', 1)->where('isDelete', 1)->first();
        } else {
            $rights = UsertypeRightsModel::where('user_type_id', $admin_details->id)->where('isActive', 1)->where('isDelete', 1)->first();
        }
        if($rights){
            $rights = $rights->toArray();
            $rights = json_decode($rights['rights'], true);
            $admin_details['rights'] = $rights;
        }else{
            $admin_details['rights'] =[];
        }
        return $admin_details;
    }

    public static function upChain($id){
        $user_details = UsersModel::find($id);
        $data = [];
        if($user_details){
            $user_details = $user_details->toArray();
        }
        if($user_details['user_type'] == 1){
            $data['uper_chain_ids'] = $id;
            $data['uper_chain'] = $id;
        }else{
            $uperChainArrya = explode(",",$user_details['uper_chain_ids']);
            array_push($uperChainArrya,$id);
            $data['uper_chain_ids'] = implode(",",$uperChainArrya);
            $data['uper_chain'] = implode("->",$uperChainArrya);
        }
        return $data;
    }

    public static function get_lowerChain($id, $onlyIds = false, $createChain = false)
    {
        $data = [];

        $user_details = UsersModel::find($id);

        if ($user_details) {
            $admin_details = UsersTypeModel::find($user_details->user_type);
            // dd($admin_details);

            $lower_chain = UsersTypeModel::select(["id", "name", "chain_flow"])
                ->where('chain_flow', ">", $admin_details->chain_flow)
                ->orderBy('chain_flow')
                ->get()
                ->toArray();
            if ($onlyIds) {
                $data = array_column($lower_chain, 'id');
            } else {
                $data = $lower_chain;
            }
        }
        return $data;
    }

    public static function checkRight($right, $pageId = 0)
    {
        if(session("authentication.user_type_id") != 1){
            if($pageId > 0){

                if ($right != "") {
                    if (session("authentication.rights.". $right . "_" . $pageId) == "1") {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            }else{
                // dd($pageId);
                return false;
            }
        }else{
            return true;
        }
    }

    public static function getPersonalUser($user_id, $fields = ['*']) {

        $data = UsersModel::select($fields)->where('isDelete', 1)->where('createdBy', $user_id)->get()->toArray();

        return $data;

    }

    public static function getChainWishUser($user_id, $fields = ['*'], $singleArray = false) {

        $data = UsersModel::select($fields)->where('isDelete', 1)->whereRaw('FIND_IN_SET(?, uper_chain_ids)', [$user_id])->get()->toArray();

        $data = $singleArray == true ? $data->toArray() : $data;

        return $data;

    }

    public static function getAllUser($user_id, $fields = ['*']) {

        $data = UsersModel::select($fields)->where('isDelete', 1);
        $user_type = self::getUserType($user_id);
        $data = $data->where('user_type', '>', $user_type);
        $data = $data->get()->toArray();
        return $data;

    }

    public static function getUserType($user_id = 0) {
        $user_type = "";
        if ($user_id > 0) {
            $data = UsersModel::select('user_type')->where('id', $user_id)->first();
            if ($data) {
                $user_type = $data->user_type;
            }
        }
        return $user_type;
    }

    public static function getLowerUsertype($userType = 0) {
        $data = [];
        if ($userType > 0) {
            $data = UsersTypeModel::select(["id", "name"])->where('id', ">", $userType)->orderBy('id')->pluck('name', 'id')->toArray();
        }
        return $data;
    }

    public static function upload_file($slug = "", $id = 0, $file){
        if ($slug != "" && $id > 0 && $file) {

            self::create_directory_if_not_exists(self::get_file_folder($slug));
            self::create_directory_if_not_exists(self::get_file_folder($slug) . $id . "/");

            $file_path = self::get_file_folder($slug) . $id . "/";

            $original_file_name = self::unicFileName($file->getClientOriginalName());
            $file->move($file_path, $original_file_name);

            $thumbnail_path = $file_path . 'thumb_' . $original_file_name;
            self::resizeImage($file_path . $original_file_name, $thumbnail_path, 300, 300);
            return $original_file_name;
        } else {
            return null;
        }
    }

    private static function resizeImage($original_path, $thumbnail_path, $width, $height)
    {
        // Check image type
        $image_type = exif_imagetype($original_path);

        if ($image_type === IMAGETYPE_JPEG) {
            $source = imagecreatefromjpeg($original_path);
        } elseif ($image_type === IMAGETYPE_PNG) {
            $source = imagecreatefrompng($original_path);
        } elseif ($image_type === IMAGETYPE_GIF) {
            $source = imagecreatefromgif($original_path);
        } else {
            // Handle unsupported image type or show an error message
            die('Unsupported image type');
        }

        list($original_width, $original_height) = getimagesize($original_path);

        $thumb = imagecreatetruecolor($width, $height);

        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $width, $height, $original_width, $original_height);

        // Create a transparent round mask
        $mask = imagecreatetruecolor($width, $height);
        $mask_color = imagecolorallocate($mask, 255, 255, 255);
        imagefilledellipse($mask, $width / 2, $height / 2, $width, $height, $mask_color);
        imagecolortransparent($mask, $mask_color);

        // Apply the mask to the thumbnail
        imagecopymerge($thumb, $mask, 0, 0, 0, 0, $width, $height, 100);
        imagecolortransparent($thumb, $mask_color);

        // Save the rounded thumbnail with higher quality
        imagejpeg($thumb, $thumbnail_path, 90); // Adjust the quality as needed

        imagedestroy($thumb);
        imagedestroy($source);
        imagedestroy($mask);
    }

    public static function create_directory_if_not_exists($directory_path) {
        if (!file_exists($directory_path) || !is_dir($directory_path)) {
            if (mkdir($directory_path, 0755, true)) {
                return true;
            } else {
                // Debugging
                var_dump(error_get_last());
                return false;
            }
        } else {
            return true;
        }
    }

    public static function get_file_folder($slug = ""){
        switch ($slug) {
            case "profile_picture":
                return app('profile_folder');
            default:
                return "";
        }
    }

    public static function unicFileName($file_name = "")
    {
        $extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $unic_file_name = uniqid() . '.' . $extension;
        return $unic_file_name;
    }

    public static function getFilePath($slug, $file_name){

        $file = app('default_image');
        if($slug){
            $file_folder = self::get_file_folder($slug);
            if($file_folder){
                $file_path = $file_folder.$file_name;
                if (file_exists($file_path) || is_dir($file_path)) {
                    $file = app('profile_picture').'/'.$file_name;
                }
            }
        }
        return $file;
    }

}
