<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Created by PhpStorm.
 * User: srama
 * Date: 11/14/2016
 * Time: 1:40 AM
 */
if(!class_exists('DAContainer')){
	class DAContainer{
		public static $registry = array();

		/**
		 * Add a new resolver to the registry array.
		 * @param  string $name The id
		 * @param  object $resolve Closure that creates instance
		 * @return void
		 */
		public static function register($name, Closure $resolve)
		{
			static::$registry[$name] = $resolve;
		}

		/**
		 * Create the instance
		 * @param  string $name The id
		 * @return mixed
		 */
		public static function resolve($name)
		{
			if ( static::registered($name) )
			{
				$name = static::$registry[$name];
				return $name();
			}

			throw new Exception('Nothing registered with that name, fool.');
		}

		/**
		 * Determine whether the id is registered
		 * @param  string $name The id
		 * @return bool Whether to id exists or not
		 */
		public static function registered($name)
		{
			return array_key_exists($name, static::$registry);
		}
	}
}


/* user
// Add `photo` to the registry array, along with a resolver
IoC::register('photo', function() {
    $photo = new Photo;
    $photo->setDB('...');
    $photo->setConfig('...');

    return $photo;
});

// Fetch new photo instance with dependencies set
$photo = IoC::resolve('photo');

*/