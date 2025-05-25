<?php

namespace App\Libraries\WPServer\Core;

use RuntimeException;

/**
 * Exception thrown when the server fails to parse a plugin/theme.
 */
class InvalidPackageException extends RuntimeException { }
