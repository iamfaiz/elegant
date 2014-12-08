<?php namespace Core\Framework;

class View
{
	public static function render( $viewName, array $data=array() )
	{
		$twigLoader = new \Twig_Loader_Filesystem(APP_PATH . '/app/views');
		$twig       = new \Twig_Environment($twigLoader);
		return $twig->render( $viewName, $data );
	}
}