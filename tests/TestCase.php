<?php

namespace TheLHC\AuthNetClient\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use TheLHC\AuthNetClient\AuthNetClientServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use Dotenv\Dotenv;

class TestCase extends BaseTestCase
{

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $dotenv = new Dotenv(__DIR__);
        $dotenv->load();
        $app['config']->set(
            'auth_net_client.endpoint',
            getenv('AUTHORIZENET_ENDPOINT')
        );
        $app['config']->set(
            'auth_net_client.api_name',
            getenv('AUTHORIZENET_API_NAME')
        );
        $app['config']->set(
            'auth_net_client.api_key',
            getenv('AUTHORIZENET_API_KEY')
        );
    }

    /**
     * Get package service providers.
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            AuthNetClientServiceProvider::class
        ];
    }

}
