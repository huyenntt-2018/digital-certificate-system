<?php

namespace App\Repositories\User;

use App\Repositories\BaseRepository as BaseRepository;
use Image;
use Storage;
use DB;

/**
 *
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function model()
    {
        return \App\User::class;
    }

    public function handleUploadImage($boolean = false, $image, $user = null)
    {
        if($boolean == true) {
            if(!is_null($image)) {
                $avatar = $image;
                $filename = time().'.'.$avatar->getClientOriginalExtension();
                $location = public_path('assets/images/'.$filename);
                Image::make($avatar)->resize(200, 200)->save($location);
                return $this->avatar = $filename;
            } else {
                return $this->avatar = 'images.jpeg';
            }
        } else {
            if (!is_null($image)) {
                $avatar = $image;
                $filename = time().'.'.$avatar->getClientOriginalExtension();
                $location = public_path('assets/images/'.$filename);
                Image::make($avatar)->resize(200, 200)->save($location);
                if ($user->avatar == 'images.jpeg') {
                    $user->avatar = $filename;
                    return $filename;
                } else {
                    // get the old photo
                    $oldImage = $user->avatar;
                    // update the database
                    $user->avatar = $filename;
                    // delete the old photo
                    Storage::delete($oldImage);
                    return $this->avatar = $filename;
                }
            } else {
                return $this->avatar = $user->avatar;
            }
        }
    }

    public function getAllAdmin()
    {
        return \App\User::select('id', 'name')->where('role_id', 1)->get();
    }
}
