<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */

//Globals
use App\Entities\UserEntity;
use App\Models\UsersModel;

$_GLOBAL_USERS = []; //Caches users entity
$_GLOBAL_OPTIONS = []; //Caches options in the get_option function
$_GLOBAL_USER = null; //A single logged in user
$_GLOBAL_USER_ID = NULL;
function user_id(): ?int
{
    global $_GLOBAL_USER_ID;
    if (isset($_GLOBAL_USER_ID)) {
        return $_GLOBAL_USER_ID;
    }
    $_GLOBAL_USER_ID = (new \App\Libraries\Auth())->getUserId();

    return $_GLOBAL_USER_ID;
}

function user(): UserEntity|null
{
    global $_GLOBAL_USER;

    if($_GLOBAL_USER == null) {
        $_GLOBAL_USER = (new UsersModel())->find(user_id());
    }
    return $_GLOBAL_USER;
}

// ==================================
//  PLUGIN META
// ==================================

/**
 * Extract plugin metadata from a plugin's ZIP file and transform it into a structure
 * compatible with the custom update checker.
 *
 * Deprecated. Included for backwards-compatibility.
 *
 * This is an utility function that scans the input file (assumed to be a ZIP archive)
 * to find and parse the plugin's main PHP file and readme.txt file. Plugin metadata from
 * both files is assembled into an associative array. The structure if this array is
 * compatible with the format of the metadata file used by the custom plugin update checker
 * library available at the below URL.
 *
 * @see http://w-shadow.com/blog/2010/09/02/automatic-updates-for-any-plugin/
 * @see https://spreadsheets.google.com/pub?key=0AqP80E74YcUWdEdETXZLcXhjd2w0cHMwX2U1eDlWTHc&authkey=CK7h9toK&hl=en&single=true&gid=0&output=html
 *
 * Requires the ZIP extension for PHP.
 * @see http://php.net/manual/en/book.zip.php
 *
 * @param string|array $packageInfo Either path to a ZIP file containing a WP plugin, or the return value of analysePluginPackage().
 * @return array Associative array
 */
function getPluginPackageMeta($packageInfo){
    if ( is_string($packageInfo) && file_exists($packageInfo) ){
        $packageInfo = WshWordPressPackageParser::parsePackage($packageInfo, true);
    }

    $meta = array();

    if ( isset($packageInfo['header']) && !empty($packageInfo['header']) ){
        $mapping = array(
            'Name' => 'name',
            'Version' => 'version',
            'PluginURI' => 'homepage',
            'Author' => 'author',
            'AuthorURI' => 'author_homepage',
        );
        foreach($mapping as $headerField => $metaField){
            if ( array_key_exists($headerField, $packageInfo['header']) && !empty($packageInfo['header'][$headerField]) ){
                $meta[$metaField] = $packageInfo['header'][$headerField];
            }
        }
    }

    if ( !empty($packageInfo['readme']) ){
        $mapping = array('requires', 'tested');
        foreach($mapping as $readmeField){
            if ( !empty($packageInfo['readme'][$readmeField]) ){
                $meta[$readmeField] = $packageInfo['readme'][$readmeField];
            }
        }
        if ( !empty($packageInfo['readme']['sections']) && is_array($packageInfo['readme']['sections']) ){
            foreach($packageInfo['readme']['sections'] as $sectionName => $sectionContent){
                $sectionName = str_replace(' ', '_', strtolower($sectionName));
                $meta['sections'][$sectionName] = $sectionContent;
            }
        }

        //Check if we have an upgrade notice for this version
        if ( isset($meta['sections']['upgrade_notice']) && isset($meta['version']) ){
            $regex = "@<h4>\s*" . preg_quote($meta['version']) . "\s*</h4>[^<>]*?<p>(.+?)</p>@i";
            if ( preg_match($regex, $meta['sections']['upgrade_notice'], $matches) ){
                $meta['upgrade_notice'] = trim(strip_tags($matches[1]));
            }
        }
    }

    if ( !empty($packageInfo['pluginFile']) ){
        $meta['slug'] = strtolower(basename(dirname($packageInfo['pluginFile'])));
    }

    return $meta;
}