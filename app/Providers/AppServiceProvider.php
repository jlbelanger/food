<?php

namespace App\Providers;

use App\Http\Kernel;
use App\Models\Food;
use App\Models\User;
use App\Observers\FoodObserver;
use App\Observers\UserObserver;
use DB;
use Illuminate\Support\ServiceProvider;
use Log;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Registers any application services.
	 *
	 * @return void
	 */
	public function register()
	{
	}

	/**
	 * Bootstraps any application services.
	 *
	 * @param  Kernel $kernel
	 * @return void
	 */
	public function boot(Kernel $kernel)
	{
		if (config('logging.database')) {
			DB::listen(function ($q) {
				$trace = debug_backtrace();
				$source = null;
				foreach ($trace as $t) {
					if (!empty($t['file']) && strpos($t['file'], '/vendor/') === false) {
						$source = $t['file'] . ':' . $t['line'];
						break;
					}
				}
				Log::channel('database')->info(json_encode([
					'ms' => $q->time,
					'q' => $q->sql,
					'bindings' => $q->bindings,
					'source' => $source,
				]));
			});
		}

		if ($this->app->environment() !== 'local') {
			$kernel->appendMiddlewareToGroup('api', \Illuminate\Routing\Middleware\ThrottleRequests::class);
		}

		Food::observe(FoodObserver::class);
		User::observe(UserObserver::class);
	}
}
