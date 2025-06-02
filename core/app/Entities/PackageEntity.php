<?php

namespace App\Entities;

use App\Libraries\WPServer\Core\Package;
use App\Models\PackageOptionsModel;
use App\Models\PackagesModel;
use App\Models\PackageVersionsModel;
use App\Models\UpdateLogsModel;
use CodeIgniter\Entity\Entity;
use CodeIgniter\Files\File;

class PackageEntity extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];
    protected $_options = [];
    private $_latestVersion;

    public function getPackageLabel()
    {
        if ($this->attributes['type'] == 'plugin') {
            return '<span class="badge bg-primary fs-6">Plugin</span>';
        }

        return '<span class="badge bg-orange fs-6">Theme</span>';
    }
    public function getSections()
    {
        $sections = $this->attributes['sections'];
        if (empty($sections)) return [
            'description'   => [
                'name'  => 'Description',
                'content'  => $this->attributes['title'],
            ]
        ];

        $sections = json_decode($sections, true);

        if (empty($sections)) return [
            'description'   => [
                'name'  => 'Description',
                'content'  => $this->attributes['title'],
            ]
        ];

        return $sections;
    }

    public function getFile()
    {
        //Return latest file
        if (!isset($this->_latestVersion)) {
            $model = model(PackageVersionsModel::class);
            $latest = $model->where('package_id', $this->attributes['id'])
                ->orderBy('id', 'DESC')->first();

            $this->_latestVersion = $latest;
        }

        return WP_SERVER_DIRECTORY.'packages'.DIRECTORY_SEPARATOR.$this->_latestVersion->file;
    }

    public function getActiveInstalls()
    {
        return model(UpdateLogsModel::class)->groupBy('site_url')->where('slug', $this->attributes['slug'])->countAllResults();
    }

    public function getSitesInstalled()
    {
        $model = model(UpdateLogsModel::class);

//        This works, but it shows last entry of each day
//        $subQuery = $model->selectMax('created_at')
//            ->select('DATE(created_at) as day')
//            ->groupBy('day')->builder()
//            ->getCompiledSelect();
//        return $model->join("($subQuery) as latest", 'DATE(update_logs.created_at) = latest.day AND update_logs.created_at = latest.created_at')->findAll();
//
        $subQuery = $model->select('site_url, MAX(created_at) as latest_created_at')
            ->where('slug', $this->attributes['slug'])
            ->groupBy('site_url')->builder()
            ->getCompiledSelect();

        return $model->join("($subQuery) as latest",
            'update_logs.site_url = latest.site_url AND update_logs.created_at = latest.latest_created_at')->findAll();

    }

    public function getVersion()
    {
        if (!isset($this->_latestVersion)) {
            $model = model(PackageVersionsModel::class);
            $latest = $model->where('package_id', $this->attributes['id'])
                ->orderBy('id', 'DESC')->first();

            $this->_latestVersion = $latest;
        }

        return $this->_latestVersion->version;
    }

    public function getOtherVersions()
    {
        $model = model(PackageVersionsModel::class);
        return $model->where('package_id', $this->attributes['id'])
            ->orderBy('id', 'DESC')->findAll();
    }

    public function getFileSize() {
        return filesize($this->getFile());
    }

    public function getLastModified() {
        return filemtime($this->getFile());
    }

    public function getMetadata()
    {
        $packageMeta = (new Package($this->attributes['slug'], $this->getFile()))->getMetadata();

        $sections = $this->getSections();
        foreach ($sections as $section) {
            $packageMeta['sections'][$section['name']] = $section['content'];
        }

        //Add Installs
        $packageMeta['active_installs'] = 87;
        $packageMeta['rating'] = 87;
        $packageMeta['num_ratings'] = 121;
        $packageMeta['upgrade_notice'] = "This is the upgrade notice";

        return $packageMeta;
    }

    public function generateDownloadUrl()
    {
        $args = array(
            'action' => 'download',
            'slug'   => $this->attributes['slug'],
        );

        $url = route('packages.updates');

        if ( !isset($url) ) {
            $url = self::guessServerUrl();
        }
        if ( strpos($url, '?') !== false ) {
            $parts = explode('?', $url, 2);
            $base = $parts[0] . '?';
            parse_str($parts[1], $query);
        } else {
            $base = $url . '?';
            $query = array();
        }

        $query = array_merge($query, $args);

        //Remove null/false arguments.
        $query = array_filter($query, function ($value) {
            return ($value !== null) && ($value !== false);
        });

        return $base . http_build_query($query, '', '&');
    }

    public function generateUpdateUrl()
    {
        $args = array(
            'action' => 'get_metadata',
            'slug'   => $this->attributes['slug'],
        );

        $url = route('packages.updates');

        if ( !isset($url) ) {
            $url = self::guessServerUrl();
        }
        if ( strpos($url, '?') !== false ) {
            $parts = explode('?', $url, 2);
            $base = $parts[0] . '?';
            parse_str($parts[1], $query);
        } else {
            $base = $url . '?';
            $query = array();
        }

        $query = array_merge($query, $args);

        //Remove null/false arguments.
        $query = array_filter($query, function ($value) {
            return ($value !== null) && ($value !== false);
        });

        return $base . http_build_query($query, '', '&');
    }

    public function getBanners()
    {
        $banners = $this->attributes['banners'] ?: json_encode([]);
        $banners = json_decode($banners, true);
        if (!$banners) {
            $banners = [
                'low'   => package_assets_url('banners/default-banner-low.jpg'),
                'high'   => package_assets_url('banners/default-banner-high.jpg'),
            ];
        }

        return $banners;
    }

    public function getIcons()
    {
        $icons = $this->attributes['icons'] ?: json_encode([]);
        $icons = json_decode($icons, true);
        if (!$icons) {
            $icons = [
                '1x'    => package_assets_url('icons/default-icon-128x128.png'),
                '2x'    => package_assets_url('icons/default-icon-256x256.png'),
            ];
        }

        $icons = array_filter($icons);
        if ( !empty($icons) ) {
            return $icons;
        }

        return null;
    }

    /**
     * OPTIONS
     */

    public function hasOption($option)
    {
        if (isset($this->_options[$option])) {
            return true;
        }

        $packageOptionsModel = model(PackageOptionsModel::class);

        $get = $packageOptionsModel->where('package_id', $this->attributes['id'])->where('option_name', $option)->first();
        if ($get) {
            $this->_options[$option] = $get->option_value;
            return true;
        }
        return false;
    }

    public function getOption($option, $default = false)
    {
        if (isset($this->_options[$option])) {
            return $this->_options[$option];
        }

        $packageOptionsModel = model(PackageOptionsModel::class);

        $get = $packageOptionsModel->where('package_id', $this->attributes['id'])->where('option_name', $option)->first();
        if ($get) {
            return $this->_options[$option] = $get->option_value;
        }
        return $default;
    }

    public function updateOption($option, $value)
    {

        $packageOptionsModel = model(PackageOptionsModel::class);

        $get = $packageOptionsModel->where('package_id', $this->attributes['id'])->where('option_name', $option)->first();
        if ($get) {
            //Update
            return $packageOptionsModel->where('package_id', $this->attributes['id'])->where('option_name', $option)
                ->set('option_value', $value)
                ->update();
        }
        // Create
        return $packageOptionsModel->save([
            'package_id' => $this->attributes['id'],
            'option_name' => $option,
            'option_value' => $value
        ]);
    }
}
