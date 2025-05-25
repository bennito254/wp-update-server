<?php

namespace App\Libraries\WPServer\ExtensionMeta;

use ZipArchive;

class WpZipArchive extends Archive {
    /**
     * @var ZipArchive
     */
    protected $archive;

    protected function __construct($zipArchive) {
        $this->archive = $zipArchive;
    }

    public static function open($zipFileName) {
        $zip = new \ZipArchive();
        if ( $zip->open($zipFileName) !== true ) {
            return false;
        }
        return new self($zip);
    }

    public function listEntries() {
        $list = array();
        $zip = $this->archive;

        for ($index = 0; $index < $zip->numFiles; $index++) {
            $info = $zip->statIndex($index);
            if ( is_array($info) ) {
                $list[] = array(
                    'name'     => $info['name'],
                    'size'     => $info['size'],
                    'isFolder' => ($info['size'] == 0),
                    'index'    => $index,
                );
            }
        }

        return $list;
    }

    public function getFileContents($fileInfo) {
        return $this->archive->getFromIndex($fileInfo['index']);
    }
}