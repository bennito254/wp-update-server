<?php

namespace App\Libraries;

use App\Models\UpdateLogsModel;

class Reports
{
    public function getSoftwares()
    {
        $model = model(UpdateLogsModel::class);
        //Get all PHP versions and counts
        $installedVersions = $model->db->query("
SELECT slug AS installed_version, COUNT(DISTINCT site_url) as count
    FROM (
        SELECT site_url, slug
        FROM `update_logs`
        GROUP BY slug
    ) AS unique_slugs
    GROUP BY slug
    ORDER BY count DESC
")
            ->getResult() ;
        $wpVersions = $model->select('wp_version, COUNT(DISTINCT site_url) as count')
            ->groupBy('wp_version')
            ->orderBy('count', 'DESC')
            ->findAll();
        $phpVersions = $model->select('php_version, COUNT(DISTINCT site_url) as count')
            ->groupBy('php_version')
            ->orderBy('count', 'DESC')
            ->findAll();


        return [
            'installedVersion' => $installedVersions,
            'wpVersion' => $wpVersions,
            'phpVersion' => $phpVersions,
        ];
    }
}