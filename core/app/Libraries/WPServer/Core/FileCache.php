<?php

namespace App\Libraries\WPServer\Core;

/**
 * A simple file-based cache.
 *
 * @internal Data is base64 encoded to avoid unserialization issues ('unserialize(): Error at offset') which
 * could be caused by:
 * - inconsistent line endings
 * - unescaped quotes/slashes etc
 * - miscounted unicode characters
 *
 * @see https://github.com/YahnisElsts/wp-update-server/pull/11
 */
class FileCache implements Cache {
	//phpcs:disable WordPress.PHP.DiscouragedPHPFunctions.serialize_unserialize
	//phpcs:disable WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
	//Developers should not run an update server without a writeable cache directory.
	//phpcs:disable WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_file_put_contents

	protected $cacheDirectory;

	public function __construct($cacheDirectory) {
		$this->cacheDirectory = $cacheDirectory;
	}

	/**
	 * Get cached value.
	 *
	 * @param string $key
	 * @return mixed|null
	 */
	public function get($key) {
		$filename = $this->getCacheFilename($key);
		if ( is_file($filename) && is_readable($filename) ) {
			//phpcs:ignore WordPressVIPMinimum.Performance.FetchingRemoteData.FileGetContentsUnknown -- Not remote.
			$cache = unserialize(base64_decode(file_get_contents($filename)));
			if ( $cache['expiration_time'] < time() ) {
				/* Could cause potential non-critical race condition
				   @see https://github.com/YahnisElsts/wp-update-server/pull/22 */
				$this->clear($key);
				return null; //Cache expired.
			} else {
				return $cache['value'];
			}
		}
		return null;
	}

	/**
	 * Update the cache.
	 *
	 * @param string $key Cache key.
	 * @param mixed $value The value to store in the cache.
	 * @param int $expiration Time until expiration, in seconds. Optional.
	 * @return void
	 */
	public function set($key, $value, $expiration = 0) {
		$cache = array(
			'expiration_time' => time() + $expiration,
			'value' => $value,
		);
        $path = $this->getCacheFilename($key);
        $exploded = explode(DIRECTORY_SEPARATOR, $path);

        array_pop($exploded);

        $directoryPathOnly = implode(DIRECTORY_SEPARATOR, $exploded);

        if (!file_exists($directoryPathOnly))
        {
            mkdir($directoryPathOnly,0775,true);
        }
		file_put_contents($path, base64_encode(serialize($cache)));
	}

	/**
	 * @param string $key
	 * @return string
	 */
	protected function getCacheFilename($key) {
		return $this->cacheDirectory . '/' . $key . '.txt';
	}
	
	
	/**
	 * Clear a cache.
	 *
	 * @param string $key Cache key.
	 * @return void
	 */
	public function clear($key) {
		$file = $this->getCacheFilename($key);
		if ( is_file($file) ) {
			//The cache directory should be writeable.
			//phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_unlink
			unlink($file);
		}
	}

	//phpcs:enable
}