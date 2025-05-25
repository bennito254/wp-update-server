<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\WPServer\Core\UpdateServer;
use App\Models\PackagesModel;

class AdminController extends BaseController
{
    public mixed $data;

    public function __construct()
    {
        $this->data['site_title'] = "Dashboard";
    }

    public function index()
    {

        return $this->_renderPage('Admin/index');
    }

    public function view($slug)
    {
        $this->data['slug'] = $slug;

        $package = model(PackagesModel::class)->where('slug', $slug)->first();

        if (!$package) {
            return redirect()->back()->withInput()->with('error', 'Package not found');
        }

        $this->data['package'] = $package;
        $this->data['site_title'] = $package->title;

        return $this->_renderPage('Admin/Package/view');
    }

    public function _renderPage($view)
    {
        $this->data['_html_content'] = view($view, $this->data);

        return view('layout', $this->data);
    }
}