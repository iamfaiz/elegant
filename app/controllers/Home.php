<?php

class Home
{
	public function index( $user=1 )
	{
		return json_encode(User::all());
	}
}