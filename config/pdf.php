<?php

return [
	'mode'                  => 'utf-8',
	'format'                => 'A4',
	'author'                => '',
	'subject'               => '',
	'keywords'              => '',
	'creator'               => 'Laravel Pdf',
	'display_mode'          => 'fullpage',
	'font_path' => base_path('public/fonts/'),
	'font_data' => [
		'openSans' => [
			'R'  => 'OpenSans-Regular.ttf',    // regular font
			'B'  => 'OpenSans-Bold.ttf',       // optional: bold font
			'I'  => 'OpenSans-Italic.ttf',     // optional: italic font
			'BI' => 'OpenSans-BoldItalic.ttf' // optional: bold-italic font
		]
	]
];
