<?php

namespace App\Libraries;

use App\Models\UpdateLogsModel;

class Reports
{

    /**
     * Returns the access count per package in a month
     *
     * @return array
     */
    public function getSlugCountsPerMonth(): array
    {
        $model = model(UpdateLogsModel::class);
        return $model->select("DATE_FORMAT(STR_TO_DATE(`created_at`, '%Y-%m-%d %H:%i:%s'), '%Y-%m') AS month, slug, COUNT(*) AS total")
            //->groupBy("month, slug")
            ->groupBy("month")
            ->orderBy("month", "ASC")
            ->orderBy("slug", "ASC")
            ->findAll();
    }

    /**
     * Return metrics of the requesting server, installed package version, wordpress version and PHP version
     *
     * @return array
     */
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