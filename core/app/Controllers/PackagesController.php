<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\WPServer\Core\UpdateServer;

class PackagesController extends BaseController
{
    public function index() {
        $server = new UpdateServer();
        $server->handleRequest();
    }
}