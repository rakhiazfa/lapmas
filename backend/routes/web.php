<?php

use App\Http\Controllers\Web\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', DashboardController::class)->middleware('auth:web', 'role:admin,agency,employee')->name('dashboard');

/**
 * Load auth routes.
 * 
 */

require_once __DIR__ . '/web/auth.php';

/**
 * Load agency routes.
 * 
 */

require_once __DIR__ . '/web/agency.php';

/**
 * Load employee routes.
 * 
 */

require_once __DIR__ . '/web/employee.php';

/**
 * Load society routes.
 * 
 */

require_once __DIR__ . '/web/society.php';

/**
 * Load society report routes.
 * 
 */

require_once __DIR__ . '/web/society_report.php';

/**
 * Load report routes.
 * 
 */

require_once __DIR__ . '/web/report.php';
