<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\AdminController;
use App\Libraries\Mailer;

class SettingsController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['site_title'] = 'Settings';
    }

    public function index() {
        if ($data = $this->request->getPost()) {

            foreach ($data as $name => $value) {
                update_option($name, $value);
            }

            return $this->response->setContentType('application/json')
                ->setBody(json_encode([
                    'status'    => 'success',
                    'title'     => 'Success',
                    'message'   => "Settings saved successfully",
                    'notifyType'    => 'swal',
                    'callback'  => 'window.location.reload()'
                ]));
        }
        return $this->_renderPage('Admin/Settings/index');
    }

    public function testEmail()
    {
        $mailer = new Mailer();
        $subject = $this->request->getPost('subject');
        $message = $this->request->getPost('message');
        $email = $this->request->getPost('email');
        if ($mailer->sendEmail($subject, $message, $email)) {
            return $this->response->setContentType('application/json')
                ->setBody(json_encode([
                    'status'    => 'success',
                    'title'     => 'Success',
                    'message'   => "E-Mail sent successfully",
                    'notifyType'    => 'swal',
                    //'callback'  => 'window.location.reload()'
                ]));
        } else {
            $error = $mailer->ErrorInfo;
            return $this->response->setContentType('application/json')
                ->setBody(json_encode([
                    'status'    => 'error',
                    'title'     => 'Error',
                    'message'   => $error ? $error : "Something went wrong",
                    'notifyType'    => 'swal',
                    //'callback'  => 'window.location.reload()'
                ]));
        }
    }
}