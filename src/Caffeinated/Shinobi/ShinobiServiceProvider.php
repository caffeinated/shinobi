<?php
namespace Caffeinated\Shinobi;

use Illuminate\Support\ServiceProvider;

class ShinobiServiceProvider extends ServiceProvider
{
	/**
	 * Indicates of loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider
	 *
	 * @return void
	 */
	public function register()
	{
		// 
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['shinobi'];
	}
}
