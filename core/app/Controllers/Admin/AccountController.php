<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\AdminController;
use App\Controllers\AuthController;
use App\Libraries\Auth;

class AccountController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['site_title'] = 'Account Settings';
    }

    public function index() {
        if ($this->request->getPost()) {
            // Update Profile
            $data = [
                'first_name'    => $this->request->getPost('first_name'),
                'last_name'     => $this->request->getPost('last_name'),
                'phone'         => $this->request->getPost('phone'),
            ];
            $auth = new Auth();
            if($auth->update(user()->id, $data)) {
                user()->deleteAvatar();
                return $this->response->setContentType('application/json')
                    ->setBody(json_encode([
                        'status'    => 'success',
                        'title'     => 'Success',
                        'message'   => "Account updated successfully",
                        'notifyType'    => 'toastr',
                        'callback'  => 'window.location.reload()'
                    ]));
            }

            return $this->response->setContentType('application/json')
                ->setBody(json_encode([
                    'status'    => 'error',
                    'title'     => 'Error',
                    'message'   => "Failed to update",
                    'notifyType'    => 'swal',
                    //'callback'  => 'window.location.reload()'
                ]));
        }

        return $this->_renderPage('Admin/Account/Settings');
    }

    public function security() {
        //Change password
        if ($this->request->getPost()) {
            // Update Profile
            if (!password_verify($this->request->getPost('old_password'), user()->password)) {
                return $this->response->setContentType('application/json')
                    ->setBody(json_encode([
                        'status'    => 'error',
                        'title'     => 'Failed',
                        'message'   => "Current password is wrong",
                        'notifyType'    => 'toastr'
                    ]));
            }

            if ($this->request->getPost('new_password') != $this->request->getPost('confirm_new_password')) {
                return $this->response->setContentType('application/json')
                    ->setBody(json_encode([
                        'status'    => 'error',
                        'title'     => 'Failed',
                        'message'   => "New password and confirmation does not match",
                        'notifyType'    => 'toastr'
                    ]));
            }

            //Check old password

            $data = [
                'password'    => $this->request->getPost('new_password'),
            ];
            $auth = new Auth();
            if($auth->update(user()->id, $data)) {
                return $this->response->setContentType('application/json')
                    ->setBody(json_encode([
                        'status'    => 'success',
                        'title'     => 'Success',
                        'message'   => "Password changed successfully",
                        'notifyType'    => 'swal',
                        'callback'  => 'window.location.reload()'
                    ]));
            }

            return $this->response->setContentType('application/json')
                ->setBody(json_encode([
                    'status'    => 'error',
                    'title'     => 'Error',
                    'message'   => "Failed to update",
                    'notifyType'    => 'swal',
                    //'callback'  => 'window.location.reload()'
                ]));
        }

        return $this->_renderPage('Admin/Account/Security');
    }
}