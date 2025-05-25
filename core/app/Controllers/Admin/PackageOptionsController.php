<?php

namespace App\Controllers\Admin;

use App\Models\PackagesModel;

class PackageOptionsController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function toggleOptions($slug)
    {
        $package = model(packagesModel::class)->findBySlug($slug);
        if (!$package) {
            return $this->response->setContentType('application/json')->setStatusCode(404)->setBody(json_encode([
                'status' => 'error',
                'message' => 'Package not found'
            ]));
        }
        $option = $this->request->getPost('option');
        //Toggle options
        $value = '1';
        if($package->getOption($option, '0') == '0') {
            $value = '1';
        } else {
            $value = '0';
        }

        if($package->updateOption($option, $value)) {
            return $this->response->setContentType('application/json')->setStatusCode(200)->setBody(json_encode([
                'status' => 'success',
                "title" => "Success",
                "notifyType" => "toastr",
                'message' => 'Option updated successfully',
            ]));
        }

        return $this->response->setContentType('application/json')->setStatusCode(200)->setBody(json_encode([
            'status' => 'error',
            "title" => "Error",
            "notifyType" => "toastr",
            'message' => 'Could not update option',
        ]));
    }
}