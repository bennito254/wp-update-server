<?php
session_start();

function step() {
    return isset($_POST['step']) ? (int)$_POST['step'] : 1;
}

function env_exists() {
    return file_exists(__DIR__ . '/core/.env');
}

function try_db_connection($host, $port, $name, $user, $pass) {
    try {
        new PDO("mysql:host=$host;port=$port;dbname=$name", $user, $pass);
        return true;
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}

function save_env($data) {
    $env = <<<ENV
CI_ENVIRONMENT = production
database.default.hostname={$data['db_host']}
database.default.database={$data['db_name']}
database.default.username={$data['db_user']}
database.default.password={$data['db_pass']}
database.default.DBDriver=MySQLi
database.default.DBPrefix={$data['db_prefix']}
database.default.port={$data['db_port']}
ENV;
    file_put_contents(__DIR__ . '/core/.env', $env);
}

function run_install($admin, $db) {
    $dsn = "mysql:host={$db['db_host']};port={$db['db_port']};dbname={$db['db_name']}";
    $pdo = new PDO($dsn, $db['db_user'], $db['db_pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $prefix = $db['db_prefix'];
    $sql = <<<INSTALL_SCHEMA
-- Adminer 5.3.0 MariaDB 10.11.11-MariaDB-0+deb12u1 dump
SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';
DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
      `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
      `name` varchar(20) NOT NULL,
      `description` varchar(100) NOT NULL,
      PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
INSERT INTO `groups` (`id`, `name`, `description`) VALUES
   (1,	'admin',	'Administrator'),
   (2,	'members',	'Members');
SET NAMES utf8mb4;
DROP TABLE IF EXISTS `ip_addresses`;
CREATE TABLE `ip_addresses` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `ip_address` varchar(45) DEFAULT NULL,
            `country` varchar(100) DEFAULT NULL,
            `country_code` varchar(10) DEFAULT NULL,
            `city` varchar(100) DEFAULT NULL,
            `timezone` varchar(100) DEFAULT NULL,
            `lat_long` varchar(50) DEFAULT NULL,
            `isp` varchar(100) DEFAULT NULL,
            `org` varchar(100) DEFAULT NULL,
            `last_visit` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            `created_at` timestamp NULL DEFAULT current_timestamp(),
            `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE `login_attempts` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `ip_address` varchar(45) NOT NULL,
              `login` varchar(100) NOT NULL,
              `time` int(11) unsigned DEFAULT NULL,
              PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
DROP TABLE IF EXISTS `options`;
CREATE TABLE `options` (
       `id` int(11) NOT NULL AUTO_INCREMENT,
       `meta_parent` varchar(254) DEFAULT NULL,
       `meta_key` varchar(254) NOT NULL,
       `meta_value` text DEFAULT NULL,
       PRIMARY KEY (`id`),
       UNIQUE KEY `meta_key` (`meta_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
DROP TABLE IF EXISTS `packages`;
CREATE TABLE `packages` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `author` int(11) DEFAULT NULL,
        `title` varchar(255) NOT NULL,
        `slug` varchar(255) NOT NULL,
        `type` varchar(255) NOT NULL DEFAULT 'plugin',
        `banners` text DEFAULT NULL,
        `icons` text DEFAULT NULL,
        `sections` text DEFAULT NULL,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
        `deleted_at` varchar(30) NOT NULL,
        PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
DROP TABLE IF EXISTS `package_options`;
CREATE TABLE `package_options` (
               `package_id` int(11) NOT NULL,
               `option_name` varchar(255) NOT NULL,
               `option_value` text DEFAULT NULL,
               KEY `package_id` (`package_id`),
               CONSTRAINT `package_options_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
DROP TABLE IF EXISTS `package_versions`;
CREATE TABLE `package_versions` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `package_id` int(11) NOT NULL,
                `file` text NOT NULL,
                `version` varchar(30) NOT NULL,
                `metadata` text DEFAULT NULL,
                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                `deleted_at` varchar(30) DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `package_id` (`package_id`),
                CONSTRAINT `package_versions_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
DROP TABLE IF EXISTS `update_logs`;
CREATE TABLE `update_logs` (
           `ip` varchar(255) DEFAULT NULL,
           `date` varchar(255) NOT NULL,
           `http_method` varchar(30) NOT NULL,
           `action` varchar(255) NOT NULL,
           `slug` varchar(255) NOT NULL,
           `installed_version` varchar(100) DEFAULT NULL,
           `wp_version` varchar(100) DEFAULT NULL,
           `php_version` varchar(100) DEFAULT NULL,
           `site_url` varchar(255) DEFAULT NULL,
           `access_granted` tinyint(1) DEFAULT 1,
           `query` text DEFAULT NULL,
           `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
     `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
     `ip_address` varchar(45) NOT NULL,
     `alnum` varchar(45) NOT NULL,
     `token` varchar(45) NOT NULL,
     `username` varchar(100) DEFAULT NULL,
     `password` varchar(255) NOT NULL,
     `email` varchar(254) NOT NULL,
     `activation_selector` varchar(255) DEFAULT NULL,
     `activation_code` varchar(255) DEFAULT NULL,
     `forgotten_password_selector` varchar(255) DEFAULT NULL,
     `forgotten_password_code` varchar(255) DEFAULT NULL,
     `forgotten_password_time` int(11) unsigned DEFAULT NULL,
     `remember_selector` varchar(255) DEFAULT NULL,
     `remember_code` varchar(255) DEFAULT NULL,
     `created_on` int(11) unsigned NOT NULL,
     `last_login` int(11) unsigned DEFAULT NULL,
     `active` tinyint(1) unsigned DEFAULT NULL,
     `first_name` varchar(50) DEFAULT NULL,
     `middle_name` varchar(50) DEFAULT NULL,
     `last_name` varchar(50) DEFAULT NULL,
     `phone` varchar(20) DEFAULT NULL,
     `city` varchar(255) DEFAULT NULL,
     `state` varchar(255) DEFAULT NULL,
     `avatar` varchar(255) DEFAULT NULL,
     `two_factor` int(1) NOT NULL DEFAULT 0,
     `two_factor_secret` varchar(200) DEFAULT NULL,
     PRIMARY KEY (`id`),
     UNIQUE KEY `uc_email` (`email`),
     UNIQUE KEY `uc_activation_selector` (`activation_selector`),
     UNIQUE KEY `uc_forgotten_password_selector` (`forgotten_password_selector`),
     UNIQUE KEY `uc_remember_selector` (`remember_selector`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
DROP TABLE IF EXISTS `users_groups`;
CREATE TABLE `users_groups` (
            `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `user_id` int(11) unsigned NOT NULL,
            `group_id` mediumint(8) unsigned NOT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
            KEY `fk_users_groups_users1_idx` (`user_id`),
            KEY `fk_users_groups_groups1_idx` (`group_id`),
            CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
            CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
-- 2025-06-02 12:36:29 UTC
INSTALL_SCHEMA;

    // Improved prefix injection using regex
    $replacements = [
        // Handle all standard table references (except those in FOREIGN KEY constraints)
        '/(DROP TABLE IF EXISTS|CREATE TABLE|INSERT INTO|FROM|INTO|JOIN|ALTER TABLE|RENAME TABLE)\s+`?(\w+)`?/i'
        => function($matches) use ($prefix) {
            // Skip prefixing if it's a MySQL keyword
            if (in_array(strtoupper($matches[2]), ['SELECT', 'UPDATE', 'DELETE', 'WHERE', 'SET', 'VALUES', 'ON'])) {
                return $matches[0];
            }
            return $matches[1] . ' `' . $prefix . $matches[2] . '`';
        },
        // Special handling for FOREIGN KEY constraints to avoid double prefixing
        '/(REFERENCES|CONSTRAINT `\w+` FOREIGN KEY \(.*?\) REFERENCES)\s+`?(\w+)`?/i'
        => function($matches) use ($prefix) {
            // Only prefix if not already prefixed
            if (strpos($matches[2], $prefix) !== 0) {
                return $matches[1] . ' `' . $prefix . $matches[2] . '`';
            }
            return $matches[1] . ' `' . $matches[2] . '`';
        },
    ];

    foreach ($replacements as $pattern => $replacement) {
        if (is_callable($replacement)) {
            $sql = preg_replace_callback($pattern, $replacement, $sql);
        } else {
            $sql = preg_replace($pattern, $replacement, $sql);
        }
    }

    // Split and execute queries without transactions
    $queries = array_filter(array_map('trim', explode(";\n", $sql)));
    foreach ($queries as $query) {
        if (!empty($query)) {
            $pdo->exec($query);
        }
    }

    // Insert Admin user
    $stmt = $pdo->prepare("INSERT INTO `{$prefix}users` 
        (ip_address, alnum, token, username, password, email, created_on, active, first_name, last_name, phone)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->execute([
        $_SERVER['REMOTE_ADDR'],
        'aAdDmMiInN',
        bin2hex(random_bytes(6)),
        strtolower($admin['email']),
        password_hash($admin['password'], PASSWORD_BCRYPT),
        $admin['email'],
        time(),
        1,
        $admin['first_name'],
        $admin['last_name'],
        $admin['phone']
    ]);

    $userId = $pdo->lastInsertId();

    // Assign admin to group 1
    $stmt2 = $pdo->prepare("INSERT INTO `{$prefix}users_groups` (user_id, group_id) VALUES (?, ?)");
    $stmt2->execute([$userId, 1]);
}

$error = '';
$step = step();

// Step logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($step === 2) {
        $_SESSION['db'] = [
            'db_host' => $_POST['db_host'],
            'db_port' => $_POST['db_port'],
            'db_name' => $_POST['db_name'],
            'db_user' => $_POST['db_user'],
            'db_pass' => $_POST['db_pass'],
            'db_prefix' => $_POST['db_prefix'],
        ];
        $test = try_db_connection(...array_values($_SESSION['db']));
        if ($test !== true) {
            $error = "Connection failed: $test";
            $step = 2;
        } else {
            $step = 3;
        }
    } elseif ($step === 3) {
        $_SESSION['admin'] = $_POST;
        run_install($_SESSION['admin'], $_SESSION['db']);
        save_env($_SESSION['db']);
        session_destroy();
        $step = 4;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Installer</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --primary-hover: #3a56d4;
            --success: #4cc9f0;
            --danger: #f72585;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --border-radius: 8px;
            --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            color: var(--dark);
            line-height: 1.6;
        }

        .container {
            background: white;
            padding: 40px;
            width: 100%;
            max-width: 500px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 {
            margin-bottom: 20px;
            color: var(--primary);
            font-weight: 600;
            position: relative;
            padding-bottom: 10px;
        }

        h2::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 3px;
            background: var(--primary);
            border-radius: 3px;
        }

        p {
            margin-bottom: 20px;
            color: var(--gray);
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }

        input {
            padding: 12px 15px;
            width: 100%;
            border: 1px solid #e0e0e0;
            border-radius: var(--border-radius);
            font-size: 15px;
            transition: var(--transition);
            background-color: var(--light);
        }

        input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }

        input::placeholder {
            color: #adb5bd;
        }

        button {
            background: var(--primary);
            color: white;
            padding: 12px;
            width: 100%;
            border: none;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        button:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
        }

        button:active {
            transform: translateY(0);
        }

        .error {
            color: var(--danger);
            background: rgba(247, 37, 133, 0.1);
            padding: 12px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            font-size: 14px;
            border-left: 3px solid var(--danger);
            animation: shake 0.5s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-5px); }
            40%, 80% { transform: translateX(5px); }
        }

        .success-message {
            background: rgba(76, 201, 240, 0.1);
            padding: 15px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            border-left: 3px solid var(--success);
        }

        .success-message p {
            margin-bottom: 0;
            color: var(--dark);
        }

        .progress-bar {
            height: 5px;
            background: #e9ecef;
            border-radius: 5px;
            margin-bottom: 30px;
            overflow: hidden;
        }

        .progress {
            height: 100%;
            background: var(--primary);
            border-radius: 5px;
            transition: width 0.5s ease;
        }

        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }

        .step-indicator::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 0;
            right: 0;
            height: 2px;
            background: #e9ecef;
            z-index: 1;
        }

        .step {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
            color: var(--gray);
            position: relative;
            z-index: 2;
        }

        .step.active {
            background: var(--primary);
            color: white;
        }

        .step.completed {
            background: var(--success);
            color: white;
        }

        .hidden {
            display: none;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 40px;
            cursor: pointer;
            color: var(--gray);
        }

        @media (max-width: 576px) {
            .container {
                padding: 25px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <?php if (env_exists() && $step === 1): ?>
        <div class="progress-bar">
            <div class="progress" style="width: 0%"></div>
        </div>
        <h2>Already Installed</h2>
        <div class="error">
            <p>The application has already been installed.</p>
            <p>Delete .env file to reinstall.</p>
        </div>
        <a href="./"><button>Go to App</button></a>

    <?php elseif ($step === 1): ?>
        <div class="progress-bar">
            <div class="progress" style="width: 0%"></div>
        </div>
        <h2>Welcome to Installer</h2>
        <p>This wizard will guide you through the installation process of your application.</p>
        <form method="post">
            <input type="hidden" name="step" value="2">
            <button type="submit">
                Start Installation &rarr;
            </button>
        </form>

    <?php elseif ($step === 2): ?>
        <div class="progress-bar">
            <div class="progress" style="width: 33%"></div>
        </div>
        <div class="step-indicator">
            <div class="step active">1</div>
            <div class="step">2</div>
            <div class="step">3</div>
        </div>
        <h2>Database Configuration</h2>
        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        <form method="post" autocomplete="off">
            <div class="form-group">
                <label for="db_host">Database Host</label>
                <input id="db_host" name="db_host" placeholder="localhost" required value="localhost">
            </div>
            <div class="form-group">
                <label for="db_port">Port</label>
                <input id="db_port" name="db_port" placeholder="3306" required value="3306">
            </div>
            <div class="form-group">
                <label for="db_name">Database Name</label>
                <input id="db_name" name="db_name" placeholder="my_database" required>
            </div>
            <div class="form-group">
                <label for="db_user">Database User</label>
                <input id="db_user" name="db_user" placeholder="root" required>
            </div>
            <div class="form-group">
                <label for="db_pass">Database Password</label>
                <input id="db_pass" name="db_pass" placeholder="Password" type="password">
                <span class="password-toggle" onclick="togglePassword('db_pass')">👁️</span>
            </div>
            <div class="form-group">
                <label for="db_prefix">Table Prefix</label>
                <input id="db_prefix" name="db_prefix" placeholder="wus_" value="wus_">
            </div>
            <input type="hidden" name="step" value="2">
            <button type="submit">
                Next &rarr;
            </button>
        </form>

    <?php elseif ($step === 3): ?>
        <div class="progress-bar">
            <div class="progress" style="width: 66%"></div>
        </div>
        <div class="step-indicator">
            <div class="step completed">✓</div>
            <div class="step active">2</div>
            <div class="step">3</div>
        </div>
        <h2>Admin Account</h2>
        <form method="post" autocomplete="off">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input id="first_name" name="first_name" placeholder="John" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input id="last_name" name="last_name" placeholder="Doe" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input id="phone" name="phone" placeholder="+1234567890" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" name="email" placeholder="admin@example.com" required type="email">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" name="password" placeholder="••••••••" type="password" required>
                <span class="password-toggle" onclick="togglePassword('password')">👁️</span>
            </div>
            <input type="hidden" name="step" value="3">
            <button type="submit">
                Finish Installation &rarr;
            </button>
        </form>

    <?php elseif ($step === 4): ?>
        <div class="progress-bar">
            <div class="progress" style="width: 100%"></div>
        </div>
        <div class="step-indicator">
            <div class="step completed">✓</div>
            <div class="step completed">✓</div>
            <div class="step completed">✓</div>
        </div>
        <h2>Installation Complete</h2>
        <div class="success-message">
            <p>Your application has been successfully installed and is ready to use.</p>
            <p>The configuration file <strong>.env</strong> has been created.</p>
            <p>PLEASE DELETE THE installer.php SCRIPT</p>
        </div>
        <a href="./"><button>Go to Application</button></a>
    <?php endif; ?>
</div>

<script>
    // Toggle password visibility
    function togglePassword(id) {
        const input = document.getElementById(id);
        const toggle = input.nextElementSibling;
        if (input.type === 'password') {
            input.type = 'text';
            toggle.textContent = '👁️';
        } else {
            input.type = 'password';
            toggle.textContent = '👁️';
        }
    }

    // Add input focus effects
    document.querySelectorAll('input').forEach(input => {
        input.addEventListener('focus', function() {
            this.parentNode.querySelector('label').style.color = '#4361ee';
        });
        input.addEventListener('blur', function() {
            this.parentNode.querySelector('label').style.color = '#212529';
        });
    });

    // Simple form validation
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            let valid = true;
            this.querySelectorAll('[required]').forEach(input => {
                if (!input.value.trim()) {
                    input.style.borderColor = '#f72585';
                    //input.nextElementSibling?.style.color = '#f72585';
                    valid = false;
                }
            });
            if (!valid) {
                e.preventDefault();
                const errorElement = this.querySelector('.error') || document.createElement('div');
                if (!this.querySelector('.error')) {
                    errorElement.className = 'error';
                    errorElement.textContent = 'Please fill in all required fields';
                    this.insertBefore(errorElement, this.firstChild);
                    setTimeout(() => {
                        errorElement.style.opacity = '1';
                    }, 10);
                }
            }
        });
    });
</script>
</body>
</html>