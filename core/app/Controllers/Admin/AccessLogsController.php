<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\AdminController;

class AccessLogsController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['site_title'] = 'Access Logs';
    }

    public function index() {

        return $this->_renderPage('Admin/AccessLogs/index');
    }
}