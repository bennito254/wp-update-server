<?php
namespace App\Libraries\WPServer\Core;


use App\Entities\PackageEntity;
use App\Libraries\WPServer\ExtensionMeta\ExtensionMeta;
use App\Models\PackagesModel;
use App\Models\PackageVersionsModel;
use App\Models\UpdateLogsModel;
use CodeIgniter\Files\File;

class UpdateServer {
	const FILE_PER_DAY = 'Y-m-d';
	const FILE_PER_MONTH = 'Y-m';

	protected $serverDirectory;
	protected $packageDirectory;
	protected $bannerDirectory;
	protected $assetDirectories = array();

	protected $logDirectory;
	protected $logRotationEnabled = false;
	protected $logDateSuffix = null;
	protected $logBackupCount = 0;

	protected $cache;
	protected $serverUrl;
	protected $startTime = 0;
	protected $packageFileLoader = [Package::class, 'fromArchive'];

	protected $ipAnonymizationEnabled = false;
	protected $ip4Mask = '';
	protected $ip6Mask = '';

	public function __construct($serverUrl = null) {
        $serverDirectory = WP_SERVER_DIRECTORY;

		$this->serverDirectory = $this->normalizeFilePath($serverDirectory);
		if ( $serverUrl === null ) {
			$serverUrl = self::guessServerUrl();
		}

		$this->serverUrl = $serverUrl;
		$this->packageDirectory = $serverDirectory . 'packages';
		$this->logDirectory = $serverDirectory . 'logs';


		$this->bannerDirectory = WP_PUBLIC_DIRECTORY . 'banners';
		$this->assetDirectories = array(
			'banners' => $this->bannerDirectory,
			'icons'   => WP_PUBLIC_DIRECTORY . 'icons',
		);

		//Set up the IP anonymization masks.
		//For 32-bit addresses, replace the last 8 bits with zeros.
		$this->ip4Mask = pack('H*', 'ffffff00');
		//For 128-bit addresses, zero out the last 80 bits.
		$this->ip6Mask = pack('H*', 'ffffffffffff00000000000000000000');

		$this->cache = new FileCache($serverDirectory . 'cache');
	}

	/**
	 * Guess the Server Url based on the current request.
	 *
	 * Defaults to the current URL minus the query and "index.php".
	 *
	 * @static
	 *
	 * @return string Url
	 */
	public static function guessServerUrl() {
        return route('packages.updates');
	}

	/**
	 * Process an update API request.
	 *
	 * @param array|null $query Query parameters. Defaults to the current GET request parameters.
	 * @param array|null $headers HTTP headers. Defaults to the headers received for the current request.
	 */
	public function handleRequest($query = null, $headers = null) {
		$this->startTime = microtime(true);

        //Returns App\Libraries\WPServer\Core\Request obj
		$request = $this->initRequest($query, $headers);
        $this->loadPackageFor($request);

        //Check Authorization
        $domain = getFullDomain($request->wpSiteUrl);
        $option = 'allow_'.$domain;
        $allowAccess = true;
        if (!$request->package->hasOption($option)) {
            //Check if we allow access on first visit
            if ($request->package->getOption('allow_access_for_new_sites', '0') == '0') {
                $allowAccess = false;
            }
        }

        if ($domain) {
            if ($request->package->getOption($option, '0') == '0') {
                //Deny
                $allowAccess = false;
            }
        }
        $request->accessGranted = $allowAccess;

        $this->logRequest($request);

        if (!$allowAccess) {
            $this->exitWithError("Access denied", 401);
        }

		$this->validateRequest($request);
		$this->dispatch($request);
		exit;
	}

	/**
	 * Set up a request instance.
	 *
	 * @param array $query
	 * @param array $headers
	 * @return Request
     */
	protected function initRequest($query = null, $headers = null) {
		if ( $query === null ) {
			//Nonce verification doesn't apply to the update server. It doesn't
			//process forms at all, or deal with stateful requests.
			//phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$query = \Config\Services::request()->getGet();
		}
		if ( $headers === null ) {
			$headers = \Config\Services::request()->headers();
		}

		//As of this writing, the client IP is only used for logging. Any more
		//advanced uses should implement additional sanitization.
		//phpcs:ignore WordPressVIPMinimum.Variables.ServerVariables.UserControlledHeaders,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        $ip = \Config\Services::request()->getIPAddress();
		$clientIp = $ip ?: '0.0.0.0';

		//Ensure that the HTTP method is always a string. That should be enough
		//sanitization for our purposes.
		//phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$httpMethod = \Config\Services::request()->getMethod(); //isset($_SERVER['REQUEST_METHOD']) ? strval($_SERVER['REQUEST_METHOD']) : 'GET';

		return new Request($query, $headers, $clientIp, $httpMethod);
	}

    /**
     * Upload the plugin file
     *
     * @return true
     * @throws \Exception
     */
    public function processUpload()
    {
        $destination = WP_SERVER_DIRECTORY.'packages'.DIRECTORY_SEPARATOR;
        $request = \Config\Services::request();
        $file = $request->getFile('plugin');
        log_message('critical', json_encode($request->getFiles()));

        $fileName = $file->getRandomName();
        if ($file->isValid() && !$file->hasMoved()) {
            if ($file->move($destination, $fileName, true)) {
                $file = new File($destination.$fileName);

                $obj = new \App\Libraries\WPServer\Core\ZipMetadataParser($file->getPathname());
                $metadata = $obj->get();

                $model = model(PackagesModel::class);

                //If slug is not available, create
                /** @var PackageEntity $package */
                $package = $model->findBySlug($metadata['slug']);
                if( !$package ) {
                    $model->save([
                        'author'        => null,
                        'title'         => $metadata['name'],
                        'slug'          => $metadata['slug'],
                        'type'          => $metadata['type'],
                        'banners'       => null,
                        'icons'         => null,
                        'sections'      => null,
                    ]);
                    $package = $model->findBySlug($metadata['slug']);
                }

                // Save this version to package_versions
                $versionsModel = model(PackageVersionsModel::class);

                if ($versionsModel->where('package_id', $package->id)->where('version', $metadata['version'])->countAllResults() == 0) {
                    $newName = $metadata['slug'].'-'.$metadata['version'].'.zip';
                    $file->move($destination, $newName, true);

                    if(file_exists($destination.$fileName)) {
                        @unlink($destination.$fileName);
                    }

                    return $versionsModel->save([
                        'package_id' => $package->id,
                        'version'    => $metadata['version'],
                        'file'   => $newName,
                        'metadata' => json_encode($metadata),
                    ]);
                }

                if(file_exists($destination.$fileName)) {
                    @unlink($destination.$fileName);
                }

                throw new \Exception('Version already exists');
            }

            throw new \Exception("Could not upload the file to server packages directory");
        }

        throw new \Exception($file->getErrorString() ?: 'Unable to upload file');
    }

	/**
	 * Load the requested package into the request instance.
	 *
	 * @param Request $request
	 */
	protected function loadPackageFor($request) {
		if ( empty($request->slug) ) {
			return;
		}

		try {
			$request->package = $this->findPackage($request->slug);
		} catch (InvalidPackageException $ex) {
			$this->exitWithError(sprintf(
				'Package "%s" exists, but it is not a valid plugin or theme. ' .
				'Make sure it has the right format (Zip) and directory structure.',
				htmlentities($request->slug)
			));
			exit;
		}
	}

	/**
	 * Basic request validation. Every request must specify an action and a valid package slug.
	 *
	 * @param Request $request
	 */
	protected function validateRequest($request) {
		if ( $request->action === '' ) {
			$this->exitWithError('You must specify an action.', 400);
		}
		if ( $request->slug === '' ) {
			$this->exitWithError('You must specify a package slug.', 400);
		}
		if ( $request->package === null ) {
			$this->exitWithError('Package not found', 404);
		}
	}

	/**
	 * Run the requested action.
	 *
	 * @param Request $request
	 */
	protected function dispatch($request) {
		if ( $request->action === 'get_metadata' ) {
			$this->actionGetMetadata($request);
		} else if ( $request->action === 'download' ) {
			$this->actionDownload($request);
		} else {
			$this->exitWithError(sprintf('Invalid action "%s".', htmlentities($request->action)), 400);
		}
	}

	/**
	 * Retrieve package metadata as JSON. This is the primary function of the custom update API.
	 *
	 * @param Request $request
	 */
	protected function actionGetMetadata(Request $request) {
		$meta = $request->package->getMetadata();
		$meta['download_url'] = $request->package->generateDownloadUrl();
		$meta['banners'] = $request->package->getBanners();
		$meta['icons'] = $request->package->getIcons();

		$meta = $this->filterMetadata($meta, $request);

		//For debugging. The update checker ignores unknown fields, so this is safe.
        // Unfortunately latest PHP does not
		//$meta['request_time_elapsed'] = sprintf('%.3f', microtime(true) - $this->startTime);

		$this->outputAsJson($meta);
		exit;
	}

	/**
	 * Filter plugin metadata before output.
	 *
	 * Override this method to customize update API responses. For example, you could use it
	 * to conditionally exclude the download_url based on query parameters.
	 *
	 * @param array $meta
	 * @param Request $request
	 * @return array Filtered metadata.
	 */
	protected function filterMetadata($meta, /** @noinspection PhpUnusedParameterInspection */ $request) {
		//By convention, un-set properties are omitted.
		$meta = array_filter($meta, function ($value) {
			return $value !== null;
		});
		return $meta;
	}

	/**
	 * Process a download request.
	 *
	 * Typically this occurs when a user attempts to install a plugin/theme update
	 * from the WordPress dashboard, but technically they could also download and
	 * install it manually.
	 *
	 * @param Request $request
	 */
	protected function actionDownload(Request $request) {
		$package = $request->package;
		header('Content-Type: application/zip');
		header('Content-Disposition: attachment; filename="' . $package->slug . '.zip"');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: ' . $package->getFileSize());

		readfile($package->getFile());
	}

	/**
	 * Find a plugin or theme by slug.
	 *
	 * @param string $slug
	 * @return PackageEntity A package object or NULL if the plugin/theme was not found.
	 */
	protected function findPackage($slug) {
		//Check if there's a slug.zip file in the package directory.
		$safeSlug = preg_replace('@[^a-z0-9\-_\.,+!]@i', '', $slug);

        /** @var PackagesModel $packageModel */
        $packageModel = model(PackagesModel::class);

        /** @var PackageEntity $package */
        $package = $packageModel->findBySlug($safeSlug);

        return $package;
	}

	/**
	 * Convert all directory separators to forward slashes.
	 *
	 * @param string $path
	 * @return string
	 */
	protected function normalizeFilePath($path) {
		if ( !is_string($path) ) {
			return $path;
		}
		return str_replace(array(DIRECTORY_SEPARATOR, '\\'), '/', $path);
	}

	/**
	 * Log an API request.
	 *
	 * @param Request $request
	 */
	protected function logRequest($request) {
        if (!$request->wpSiteUrl) return;

        $loggedIp = $request->clientIp;
        if ( $this->ipAnonymizationEnabled ) {
            $loggedIp = $this->anonymizeIp($loggedIp);
        }

        $columns = array(
            'ip'                => $loggedIp,
            'http_method'       => $request->httpMethod,
            'action'            => $request->param('action', '-'),
            'slug'              => $request->param('slug', '-'),
            'installed_version' => $request->param('installed_version', '-'),
            'wp_version'        => $request->wpVersion ?? '-',
            'php_version'       => $request->param('php'),
            'site_url'          => $request->wpSiteUrl ?? '--',
            'access_granted'    => $request->accessGranted ? '1' : '0',
            'query'             => http_build_query($request->query, '', '&'),
        );
        $columns = $this->filterLogInfo($columns, $request);
        $columns = $this->escapeLogInfo($columns);

        if ( isset($columns['ip']) ) {
            $columns['ip'] = str_pad($columns['ip'], 15, ' ');
        }
        if ( isset($columns['http_method']) ) {
            $columns['http_method'] = str_pad($columns['http_method'], 4, ' ');
        }

        //Set the time zone to whatever the default is to avoid PHP notices.
        //Will default to UTC if it's not set properly in php.ini.
        $configuredTz = ini_get('date.timezone');
        if ( empty($configuredTz) ) {
            //The update server can be used outside WP, so it can't rely on WordPress's timezone support.
            //phpcs:ignore WordPress.DateTime.RestrictedFunctions.timezone_change_date_default_timezone_set
            date_default_timezone_set(@date_default_timezone_get());
        }

        $columns['date'] = date('Y-m-d H:i:s 0');

        try {
            $updateRequestModel = model(UpdateLogsModel::class);
            $updateRequestModel->save($columns);
        } catch ( \Exception $e ) {
            log_message('critical', $e->getMessage().':'.$e->getTraceAsString());
        }
	}

	/**
	 * Adjust information that will be logged.
	 * Intended to be overridden in child classes.
	 *
	 * @param array $columns List of columns in the log entry.
	 * @param Request|null $request
	 * @return array
	 */
	protected function filterLogInfo($columns, /** @noinspection PhpUnusedParameterInspection */ $request = null) {
		return $columns;
	}

	/**
	 * Escapes passed log data so it can be safely written into a plain text file.
	 *
	 * @param string[] $columns List of columns in the log entry.
	 * @return string[] Escaped $columns.
	 */
	protected function escapeLogInfo(array $columns) {
		return array_map(array($this, 'escapeLogValue'), $columns);
	}

	/**
	 * Escapes passed value to be safely written into a plain text file.
	 *
	 * @param string|null $value Value to escape.
	 * @return string|null Escaped value.
	 */
	protected function escapeLogValue($value) {

		if ( !isset($value) ) {
			return null;
		}

		$value = (string)$value;

		$regex = '/[[:^graph:]]/';

		//preg_replace_callback will return NULL if the input contains invalid Unicode sequences,
		//so only enable the Unicode flag if the input encoding looks valid.
		/** @noinspection PhpComposerExtensionStubsInspection */
		if ( function_exists('mb_check_encoding') && mb_check_encoding($value, 'UTF-8') ) {
			$regex = $regex . 'u';
		}

		$value = str_replace('\\', '\\\\', $value);
		$value = preg_replace_callback(
			$regex,
			function (array $matches) {
				$length = strlen($matches[0]);
				$escaped = '';
				for ($i = 0; $i < $length; $i++) {
					//Convert the character to a hexadecimal escape sequence.
					$hexCode = dechex(ord($matches[0][$i]));
					$escaped .= '\x' . strtoupper(str_pad($hexCode, 2, '0', STR_PAD_LEFT));
				}
				return $escaped;
			},
			$value
		);

		return $value;
	}


	/**
	 * Anonymize an IP address by replacing the last byte(s) with zeros.
	 *
	 * @param string $ip A valid IP address such as "12.45.67.89" or "2001:db8:85a3::8a2e:370:7334".
	 * @return string
	 */
	protected function anonymizeIp($ip) {
		$binaryIp = @inet_pton($ip);
		if ( strlen($binaryIp) === 4 ) {
			//IPv4
			$anonBinaryIp = $binaryIp & $this->ip4Mask;
		} else if ( strlen($binaryIp) === 16 ) {
			//IPv6
			$anonBinaryIp = $binaryIp & $this->ip6Mask;
		} else {
			//The input is not a valid IPv4 or IPv6 address. Return it unmodified.
			return $ip;
		}
		return inet_ntop($anonBinaryIp);
	}

	/**
	 * Output something as JSON.
	 *
	 * @param mixed $response
	 */
	protected function outputAsJson($response) {
		header('Content-Type: application/json; charset=utf-8');
		if ( defined('JSON_PRETTY_PRINT') ) {
			$output = $this->jsonEncode($response, JSON_PRETTY_PRINT);
		} elseif ( function_exists('wsh_pretty_json') ) {
			$output = wsh_pretty_json($this->jsonEncode($response));
		} else {
			$output = $this->jsonEncode($response);
		}
		//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- The output is JSON, not HTML.
		echo $output;
	}

	protected function jsonEncode($value, $flags = 0) {
		if ( function_exists('wp_json_encode') ) {
			return wp_json_encode($value, $flags);
		} else {
			//Fall back to the native json_encode() when running outside of WordPress.
			//phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode
			return json_encode($value, $flags);
		}
	}

	/**
	 * Stop script execution with an error message.
	 *
	 * @param string $message Error message. It should already be HTML-escaped. This method will not sanitize it.
	 * @param int $httpStatus Optional HTTP status code. Defaults to 500 (Internal Server Error).
	 */
	protected function exitWithError($message = '', $httpStatus = 500) {
		$statusMessages = array(
			// This is not a full list of HTTP status messages. We only need the errors.
			// [Client Error 4xx]
			400 => '400 Bad Request',
			401 => '401 Unauthorized',
			402 => '402 Payment Required',
			403 => '403 Forbidden',
			404 => '404 Not Found',
			405 => '405 Method Not Allowed',
			406 => '406 Not Acceptable',
			407 => '407 Proxy Authentication Required',
			408 => '408 Request Timeout',
			409 => '409 Conflict',
			410 => '410 Gone',
			411 => '411 Length Required',
			412 => '412 Precondition Failed',
			413 => '413 Request Entity Too Large',
			414 => '414 Request-URI Too Long',
			415 => '415 Unsupported Media Type',
			416 => '416 Requested Range Not Satisfiable',
			417 => '417 Expectation Failed',
			// [Server Error 5xx]
			500 => '500 Internal Server Error',
			501 => '501 Not Implemented',
			502 => '502 Bad Gateway',
			503 => '503 Service Unavailable',
			504 => '504 Gateway Timeout',
			505 => '505 HTTP Version Not Supported',
		);

		if ( !isset($_SERVER['SERVER_PROTOCOL']) || ($_SERVER['SERVER_PROTOCOL'] === '') ) {
			$protocol = 'HTTP/1.1';
		} else {
			//We'll just return the same protocol as the client used.
			//phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$protocol = strval($_SERVER['SERVER_PROTOCOL']);
		}

		//Output an HTTP status header.
		if ( isset($statusMessages[$httpStatus]) ) {
			header($protocol . ' ' . $statusMessages[$httpStatus]);
			$title = $statusMessages[$httpStatus];
		} else {
			header('X-Ws-Update-Server-Error: ' . $httpStatus, true, $httpStatus);
			$title = 'HTTP ' . $httpStatus;
		}

		if ( $message === '' ) {
			$message = $title;
		}

		//And a basic HTML error message.
		printf(
			'<html>
				<head> <title>%1$s</title> </head>
				<body> <h1>%1$s</h1> <p>%2$s</p> </body>
			 </html>',
			//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- esc_html() might not be available here.
			htmlentities($title),
			//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Should already be escaped.
			$message
		);
		exit;
	}
}
