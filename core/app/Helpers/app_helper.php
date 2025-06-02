<?php
function assets_url($uri, ...$args): string
{
    return base_url('assets/'.$uri, ...$args).'?v=0.05';
}

function package_assets_url($uri, ...$args): string
{
    return base_url('packages/'.$uri, ...$args).'?v=0.05';
}

function uploads_url($uri, ...$args): string
{
    return base_url('uploads/'.$uri, ...$args).'?v=0.05';
}

function route($name, ...$args) {
    return site_url(route_to($name, ...$args));
}

function routeAddGetParams(string $url, array $params): string
{
    // Parse the URL and extract components
    $parsedUrl = parse_url($url);

    // Extract existing query string if present
    $existingParams = [];
    if (isset($parsedUrl['query'])) {
        parse_str($parsedUrl['query'], $existingParams);
    }

    // Merge with new parameters (new ones overwrite old ones)
    $mergedParams = array_merge($existingParams, $params);

    // Build the new query string
    $newQuery = http_build_query($mergedParams);

    // Reconstruct the full URL
    $scheme   = $parsedUrl['scheme'] ?? '';
    $host     = $parsedUrl['host'] ?? '';
    $port     = isset($parsedUrl['port']) ? ':' . $parsedUrl['port'] : '';
    $path     = $parsedUrl['path'] ?? '';
    $fragment = isset($parsedUrl['fragment']) ? '#' . $parsedUrl['fragment'] : '';

    // Handle URLs that may be relative (e.g., no host or scheme)
    $baseUrl = '';
    if ($scheme && $host) {
        $baseUrl = "$scheme://$host$port$path";
    } else {
        $baseUrl = $path;
    }

    return $baseUrl . '?' . $newQuery . $fragment;
}

function getFullDomain($url): ?string
{
    if (!$url) return null;

    $parsedUrl = parse_url($url);
    return $parsedUrl['host'] ?? null;
}

function gatewayForm($gateway) {
    $settings = $gateway->settings;
    if ($settings) $settings = json_decode($settings);

    $options = json_decode($gateway->form_options);

    if (!$options) return FALSE;

    ob_start();
    foreach ($options as $field) {
        if ($field->type == 'text' || $field->type == 'number') {
            ?>
            <div class="form-group mb-3">
                <label class="form-label" for="<?php echo $field->name; ?>"><?php echo $field->title; ?></label>
                <input type="<?php echo $field->name; ?>" id="<?php echo $field->name; ?>" class="form-control" name="<?php echo $field->name; ?>" value="<?php echo $settings ? $settings->{$field->name} : $field->default ?? ''; ?>" required>
            </div>
            <?php
        } elseif ($field->type == 'select') {
            ?>
            <div class="form-group mb-3">
                <label class="form-label" for="<?php echo $field->name; ?>"><?php echo $field->title; ?></label>
                <select class="form-select" id="<?php echo $field->name; ?>" name="<?php echo $field->name; ?>" required>
                    <?php
                    foreach ($field->options as $value=>$name) {
                        ?>
                        <option <?php echo old($field->name, $settings ? $settings->{$field->name} : '') == $value ? 'selected' : '' ?> value="<?php echo $value; ?>"><?php echo $name; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <?php
        } else {
            ?>
            <div class="form-group mb-3">
                <label class="form-label" for="<?php echo $field->name; ?>"><?php echo $field->title; ?></label>
                <input type="text" id="<?php echo $field->name; ?>" class="form-control" name="<?php echo $field->name; ?>" value="<?php echo $settings ? $settings->{$field->name} : $field->default ?? ''; ?>" required>
            </div>
            <?php
        }
    }
    $html = ob_get_clean();

    return $html;
}

function uuid() {
    return _from_random(10, '123456789-ABCDEFGHIJKLMNPQRSTUVWXYZ-abcdefghijklmnopqrstuvwxyz');
}

function stringToColor($str) {
    // Generate a hash (CRC32 for shorter output)
    $hash = hash('crc32b', $str);

    // Extract RGB components from the hash
    $r = hexdec(substr($hash, 0, 2)) & 0xFF;
    $g = hexdec(substr($hash, 2, 2)) & 0xFF;
    $b = hexdec(substr($hash, 4, 2)) & 0xFF;

    // Optional: Ensure minimum brightness for better visibility
    $minBrightness = 60; // Adjust value (0-255)
    $r = max($r, $minBrightness);
    $g = max($g, $minBrightness);
    $b = max($b, $minBrightness);

    return sprintf("#%02x%02x%02x", $r, $g, $b);
}

function isPublicIpAddress($ip) {
    // List of private IP ranges to check
    $privateIpPatterns = [
        '/^127\./',          // Loopback (localhost)
        '/^10\./',           // Class A private networks
        '/^192\.168\./',     // Class C private networks
        '/^172\.(1[6-9]|2[0-9]|3[01])\./', // Class B private networks
        '/^169\.254\./'      // Link-local address
    ];

    // Validate if the input is a valid IP address
    if (!filter_var($ip, FILTER_VALIDATE_IP)) {
        return false; // Invalid IP format
    }

    // Check if the IP matches any of the private ranges
    foreach ($privateIpPatterns as $pattern) {
        if (preg_match($pattern, $ip)) {
            return false; // Invalid if it's a local/private IP
        }
    }

    return true; // Valid if it's a public IP
}


function getMinimumUploadLimit(): string
{
    // Helper to convert shorthand sizes to bytes
    $convertToBytes = function ($val) {
        $val = trim($val);
        $last = strtolower($val[strlen($val) - 1]);
        $num = (int)$val;

        switch ($last) {
            case 'g':
                $num *= 1024;
            case 'm':
                $num *= 1024;
            case 'k':
                $num *= 1024;
        }
        return $num;
    };

    $postMaxSize = ini_get('post_max_size');
    $uploadMaxFilesize = ini_get('upload_max_filesize');

    $postMaxBytes = $convertToBytes($postMaxSize);
    $uploadMaxBytes = $convertToBytes($uploadMaxFilesize);

    return ($postMaxBytes < $uploadMaxBytes) ? $postMaxSize : $uploadMaxFilesize;
}
