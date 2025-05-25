<?php

use App\Controllers\HomeController;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
//$routes->get('/',  [HomeController::class, 'index'], ['as' => 'home']);
$routes->get('/',  [\App\Controllers\AuthController::class, 'login'], ['as' => 'home']);

//Auth
$routes->group('session', function($routes) {
    $routes->add('login', [\App\Controllers\AuthController::class, 'login'], ['as' => 'auth.login']);
    $routes->add('2fa', 'AuthController::twoFactor', ['as' => 'auth.2fa']);
    $routes->add('sign-up', 'AuthController::register', ['as' => 'auth.register']);
    $routes->add('forgot-password', 'AuthController::forgotPassword', ['as' => 'auth.forgot_password']);
    $routes->add('otp', 'AuthController::appOTP', ['as' => 'auth.app_otp']);
    $routes->add('reset-password/(:any)', 'AuthController::resetPassword/$1', ['as' => 'auth.reset_password']);
    $routes->add('(:any)/activate/(:any)', 'AuthController::activate/$1/$2', ['as' => 'auth.activate']);
    $routes->get('logout', 'AuthController::logout', ['as' => 'auth.logout']);
});

$routes->group('admin', function ($routes) {
    $routes->add('/', [\App\Controllers\Admin\AdminController::class, 'index'], ['as' => 'admin.dashboard']);
    $routes->group('packages', function ($routes) {
        $routes->get('/', [\App\Controllers\Admin\PackagesController::class, 'index'], ['as' => 'admin.packages.index']);
        $routes->add('create', [\App\Controllers\Admin\PackagesController::class, 'uploadPackage'], ['as' => 'admin.package.create']);
        $routes->get('(:any)/view', [\App\Controllers\Admin\AdminController::class, 'view/$1'], ['as' => 'admin.packages.view']);
        $routes->post('(:any)/section', [\App\Controllers\Admin\PackagesController::class, 'sections/$1'], ['as' => 'admin.package.section']);
        $routes->post('(:any)/section/delete', [\App\Controllers\Admin\PackagesController::class, 'deleteSection/$1'], ['as' => 'admin.package.section.delete']);
        $routes->post('(:any)/options/toggle', [\App\Controllers\Admin\PackageOptionsController::class, 'toggleOptions'], ['as' => 'admin.package.options.toggle']);
    });
});

$routes->add('item', 'PackagesController::index', ['as' => 'packages.updates']);

$routes->addRedirect('mpesa/wc-mpesa.json', 'item?action=get_metadata&slug=woocommerce-lipa-na-mpesa');
