<?php

class Home
{
	public function index()
	{
		return View::render('home.html', [
			'Framework' => 'Elegant'
		]);
	}
}