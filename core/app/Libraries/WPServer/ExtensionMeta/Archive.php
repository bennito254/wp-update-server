<?php

namespace App\Libraries\WPServer\ExtensionMeta;

abstract class Archive {
    /**
     * Open a Zip archive.
     *
     * @param string $zipFileName
     * @return bool|Archive
     */
    public static function open($zipFileName) {
        if ( class_exists('ZipArchive', false) ) {
            return WpZipArchive::open($zipFileName);
        } else {
            return PclZipArchive::open($zipFileName);
        }
    }

    /**
     * Get the list of files and directories in the archive.
     *
     * @return array
     */
    abstract public function listEntries();

    /**
     * Get the contents of a specific file.
     *
     * @param array $file
     * @return string|false
     */
    abstract public function getFileContents($file);
}