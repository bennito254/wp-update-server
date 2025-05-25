<?php

namespace App\Libraries\WPServer\ExtensionMeta;

class PclZipArchive extends Archive {
    /**
     * @var PclZip
     */
    protected $archive;

    protected function __construct($zipFileName) {
        $this->archive = new PclZip($zipFileName);
    }

    public static function open($zipFileName) {
        if ( !class_exists('PclZip', false) ) {
            require_once dirname(__FILE__) . '/PclZip.php';
        }
        return new self($zipFileName);
    }

    public function listEntries() {
        $contents = $this->archive->listContent();
        if ( $contents === 0 ) {
            return array();
        }

        $list = array();
        foreach ($contents as $info) {
            $list[] = array(
                'name'     => $info['filename'],
                'size'     => $info['size'],
                'isFolder' => $info['folder'],
                'index'    => $info['index'],
            );
        }

        return $list;
    }

    public function getFileContents($fileInfo) {
        $result = $this->archive->extract(PCLZIP_OPT_BY_INDEX, $fileInfo['index'], PCLZIP_OPT_EXTRACT_AS_STRING);

        if ( ($result === 0) || (!isset($result[0], $result[0]['content'])) ) {
            return false;
        }

        return $result[0]['content'];
    }
}