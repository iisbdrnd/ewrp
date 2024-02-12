<?php

namespace App\Customs;

use Auth;
use Illuminate\Mail\TransportManager;
use App\Model\ProjectMailConfiguration_user;

class CustomTransportManager extends TransportManager {

    /**
     * Create a new manager instance.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
        //$this->app['config']['mail'] = $config;
    }
}
