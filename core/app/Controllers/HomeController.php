<?php

namespace App\Controllers;

use App\Entities\PackageEntity;
use App\Models\PackagesModel;

class HomeController extends BaseController
{
    public function index(): string
    {
        //$this->cachePage(600);


        return view('Home/login');
    }

    public function _renderPage($view)
    {
        $this->data['_html_content'] = view($view, $this->data);

        return view('layout', $this->data);
    }
}
