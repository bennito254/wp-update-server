# Private WordPress Plugin & Theme Update Server

This repository contains the backend codebase for a platform that provides **automatic updates for private WordPress plugins and themes**. It is designed to work seamlessly with the `plugin-update-checker` library on the client side (inside your plugin or theme), and serves update metadata and zip packages using a self-hosted solution based on `wp-update-server`.

## Features

- ✅ Self-hosted update server for WordPress plugins and themes.
- 🔒 Private updates — no need to publish on the WordPress.org repository.
- 📦 Serves `.zip` packages and update metadata to authenticated clients.
- 🧩 Compatible with the `plugin-update-checker` library.
- 🧪 Easy to test locally or on any PHP hosting.
- 📈 Extendable to support license checks, analytics, etc.

## How It Works

1. **Client Side (Plugin or Theme)**:
   - Uses the `plugin-update-checker` library.
   - Points to your update server URL (this platform) to check for updates.

2. **Server Side (This Project)**:
   - Hosts and serves JSON metadata and `.zip` archives for new versions.
   - Built on top of `wp-update-server`, which parses incoming requests and returns structured update data.
   - Optionally supports version constraints, plugin/theme types, and more.

## Getting Started

### Requirements

- PHP 7.4+
- A web server (Apache/Nginx)
- Composer (for dependency management)

### Installation

1. **Clone this repository**:
   ```bash
   git clone https://github.com/bennito254/wp-update-server.git
   cd private-wp-update-server
   ```

2. **Install dependencies**:
   ```bash
   composer install
   ```

3. **Add your plugin or theme package(s)**:
   - Upload a `.zip` file through the easy-to-use interface.
   - Everything else will be set up and updated according to the plugin/theme metadata.


### Client Side Example

In your WordPress plugin:

```php
require 'vendor/autoload.php';

$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://yourdomain.com/?action=get_metadata&slug=my-plugin',
    __FILE__,
    'my-plugin_or_theme_slug'
);
```

## Security Considerations

- Implement authentication or token validation to restrict update access.
- Use HTTPS to serve packages and metadata.
- Optionally integrate license key verification.

## Resources

- [plugin-update-checker Documentation](https://github.com/YahnisElsts/plugin-update-checker)
- [WordPress Plugin Developer Handbook](https://developer.wordpress.org/plugins/)

## License

MIT License. See `LICENSE` file for details.

---

> This platform is ideal for developers distributing premium or private WordPress plugins and themes outside the WordPress.org ecosystem.
