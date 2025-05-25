<?php

namespace App\Controllers\Admin;

use App\Entities\PackageEntity;
use App\Libraries\WPServer\Core\UpdateServer;
use App\Models\PackagesModel;

class PackagesController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['site_title'] = "Packages";
    }

    public function index()
    {

        return $this->_renderPage('Admin/Package/index');
    }

    public function uploadPackage()
    {
        $updateServer = new UpdateServer();

        try {
            $updateServer->processUpload();

            return redirect()->back()->with('message', 'Upload successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function sections($slug)
    {
        //POST request
        $model = model(PackagesModel::class);
        /** @var PackageEntity $package */
        $package = $model->where('slug', $slug)->first();

        if (!$package) {
            return $this->response->setContentType('application/json')->setStatusCode(404)->setBody(json_encode([
                'status' => 'error',
                'message' => 'Package not found'
            ]));
        }
        $sectionName = $this->request->getPost('section_name');
        $section_id = $this->request->getPost('section_id');

        if (!$section_id || $section_id == '') {
            $section_id = strtolower(random_string('alnum', 8));
        }
        $section = [
            'name'  => strtolower(str_replace(' ', '_', preg_replace('/[^a-zA-Z0-9\s]/', '', $sectionName))),
            'content'   => $this->request->getPost('section_content'),
        ];

        $existing = $package->getSections();
        $existing[$section_id] = $section;
        $update = [
            'id' => $package->id,
            'sections' => json_encode($existing),
        ];

        try {
            $model->save($update);

            return $this->response->setContentType('application/json')->setStatusCode(200)->setBody(json_encode([
                'status' => 'success',
                'message' => 'Package updated successfully'
            ]));
        } catch (\Exception $e) {
            return $this->response->setContentType('application/json')->setStatusCode(500)->setBody(json_encode([
                'status' => 'error',
                'message' => CI_DEBUG ? $e->getMessage() : "Exception Error: Something went wrong"
            ]));
        }
    }

    public function deleteSection($slug)
    {
        //POST request
        $model = model(PackagesModel::class);
        /** @var PackageEntity $package */
        $package = $model->where('slug', $slug)->first();

        if (!$package) {
            return $this->response->setContentType('application/json')->setStatusCode(404)->setBody(json_encode([
                'status' => 'error',
                'title'     => "Error",
                'message' => 'Package not found'
            ]));
        }
        $section_id = $this->request->getPost('section_id');

        if (!$section_id || $section_id == '') {
            return $this->response->setContentType('application/json')->setStatusCode(200)->setBody(json_encode([
                'status' => 'success',
                "title" => "Success",
                "notifyType" => "swal",
                'message' => 'Package updated successfully',
                'callback'  => 'window.location.reload();'
            ]));
        }

        $existing = $package->getSections();
        unset($existing[$section_id]);
        $update = [
            'id' => $package->id,
            'sections' => json_encode($existing),
        ];

        try {
            $model->save($update);

            return $this->response->setContentType('application/json')->setStatusCode(200)->setBody(json_encode([
                'status' => 'success',
                "title" => "Success",
                "notifyType" => "swal",
                'message' => 'Package updated successfully',
                'callback'  => 'window.location.reload();'
            ]));
        } catch (\Exception $e) {
            return $this->response->setContentType('application/json')->setStatusCode(500)->setBody(json_encode([
                'status' => 'error',
                'title' => "Something went wrong",
                'message' => CI_DEBUG ? $e->getMessage() : "Exception Error: Something went wrong"
            ]));
        }
    }
}