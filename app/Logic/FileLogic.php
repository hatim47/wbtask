<?php

namespace App\Logic;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileLogic
{
    private $prefix = 'storage/';
    private $public = 'public';
    private $imagePrefix = '/upload/image';

    /**
     * function to supdate team picture.
     * If team doesn't have a picture this will store image.
     * If team already have picture then it will replace with the old
     * image with the new oneand update the image_path record in db.
     *
     * @param int $team_id team id
     * @param Request $request request containing the image file
     * @param string $field_name image file input name
     */
    public function storeTeamImage(int $team_id, Request $request, string $field_name)
    {
        $team = Team::find($team_id);
        $new_image_path = Storage::disk($this->public)
            ->put($this->imagePrefix, $request->file($field_name), $this->public);

        if ($team->image_path != null) {
            $old_image_path = str_replace($this->prefix, "", $team->image_path);
            Storage::disk($this->public)->delete($old_image_path);
        }
        $team->image_path = $this->prefix . $new_image_path;
        $team->save();
    }

    /**
     * function to supdate user profuile picture.
     * If user doesn't have a profile picture this will store image.
     * If user already have picture then it will replace with the old
     * image with the new oneand update the image_path record in db.
     *
     * @param int $user_id user id
     * @param Request $request request containing the image file
     * @param string $field_name image file input name
     */
    public function storeUserImage(int $user_id, Request $request, string $field_name)
    {
        // $user = User::find($user_id);
        // $new_image_path = Storage::disk($this->public)
        //     ->put($this->imagePrefix, $request->file($field_name), $this->public);

        // if ($user->image_path != null) {
        //     $old_image_path = str_replace($this->prefix, "", $user->image_path);
        //     Storage::disk($this->public)->delete($old_image_path);
        // }
        // $user->image_path = $this->prefix . $new_image_path;
        // $user->save();
        $user = User::find($user_id);

if ($request->hasFile($field_name)) {
    // Define the upload path inside the public folder
    $uploadPath = 'image/user/';

    // Generate a unique file name
    $fileName = time() . '_' . $request->file($field_name)->getClientOriginalName();

    // Move the file to public/uploads/
    $request->file($field_name)->move(public_path($uploadPath), $fileName);

    // Delete old image if it exists
    if ($user->image_path) {
        $oldImagePath = public_path(str_replace($this->prefix, "", $user->image_path));
        if (file_exists($oldImagePath)) {
            unlink($oldImagePath); // Delete old image from public folder
        }
    }


    // Save new image path in the database
    $user->image_path = $uploadPath . $fileName; 
  
    $user->save();
    }
}
}
