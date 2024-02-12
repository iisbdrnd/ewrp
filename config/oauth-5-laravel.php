<?php

return [

	/*
	|--------------------------------------------------------------------------
	| oAuth Config
	|--------------------------------------------------------------------------
	*/

	/**
	 * Storage
	 */
	'storage' => '\\OAuth\\Common\\Storage\\Session',

	/**
	 * Consumers
	 */
	'consumers' => [

		'Facebook' => [
			'client_id'     => '',
			'client_secret' => '',
			'scope'         => ['email', 'read_friendlists', 'user_online_presence'],
		],

		'Google' => [
			'client_id'     => env('GOOGLE_CLIENT_ID'),
			'client_secret' => env('GOOGLE_SECRET_ID'),
			'scope'         => ['userinfo_email', 'userinfo_profile', 'https://www.google.com/m8/feeds/'],
		],

		'Linkedin' => [
			'client_id'     => env('LINKEDIN_CLIENT_ID'),
			'client_secret' => env('LINKEDIN_SECRET_ID'),
		],

		'Yahoo' => [
			'client_id'     => env('YAHOO_CLIENT_ID'),
			'client_secret' => env('YAHOO_SECRET_ID'),
		],

		'Facebook' => [
			'client_id'     => env('FACEBOOK_CLIENT_ID'),
			'client_secret' => env('FACEBOOK_SECRET_ID'),
		],

	]

];