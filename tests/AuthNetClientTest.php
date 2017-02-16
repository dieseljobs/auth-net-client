<?php

use TheLHC\AuthNetClient\AuthNetClient;
use TheLHC\AuthNetClient\Tests\TestCase;

class AuthNetClientTest extends TestCase
{

    public function testCanResolveFromTheContainer()
    {
        $manager = $this->app->make('TheLHC\AuthNetClient\AuthNetClient');
        $this->assertInstanceOf(AuthNetClient::class, $manager);
    }

}
