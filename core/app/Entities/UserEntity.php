<?php
namespace App\Entities;

use App\Libraries\Auth;
use App\Libraries\AvatarGenerator;
use App\Models\UsersModel;

class UserEntity extends \CodeIgniter\Entity\Entity
{
    public function getName()
    {
        return $this->attributes['first_name'].' '.$this->attributes['last_name'];
    }

    public function getAvatar()
    {
        $path = UPLOADS_DIR.'avatar'.DIRECTORY_SEPARATOR;
        if($this->attributes['avatar'] && file_exists($path.$this->attributes['avatar'])) {
            return uploads_url('avatar/'.$this->attributes['avatar']);
        } else {
            $file_name = uuid().'.svg';
            $avatar = new AvatarGenerator();
            if($avatar->avatar($this->getName(), $path.$file_name)) {
                //Update user
                try {
                    (new UsersModel())->update($this->attributes['id'], ['avatar' => $file_name]);
                } catch (\ReflectionException $e) {
                }

                return uploads_url('avatar/'.$file_name);
            } else {
                return base_url('assets/img/160x160/img6.jpg');
            }

        }

        return uploads_url('avatar/'.$this->attributes['avatar']);
    }

    public function deleteAvatar()
    {
        $path = UPLOADS_DIR.'avatar'.DIRECTORY_SEPARATOR;
        @unlink($path.$this->attributes['avatar']);
    }

    public function getGroups()
    {
        return (new Auth())->groups($this->attributes['id'])->result();
    }

    public function originalAvatar()
    {
        return $this->attributes['avatar'] ?? FALSE;
    }

}