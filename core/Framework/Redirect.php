<?php namespace Core\Framework;

class Redirect
{
	/**
	 * Redirect the user to a specific url
	 * @param  string $path [The url to redirect]
	 * @return void
	 */
	public static function to( $path )
	{
		header("Location: ". $path);
		exit;
	}

	/**
	 * Redirect back to the base url of the app
	 * @return void
	 */
	public static function home()
	{
		$config = require( APP_PATH . '/app/config/config.php' );
		$base_url = $config['base_url'];
		header("Location: " . $base_url);
		exit;
	}

}
