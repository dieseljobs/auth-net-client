<?php

namespace TheLHC\AuthNetClient\Facades;

use Illuminate\Support\Facades\Facade;

class AuthNetClient extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
      return 'TheLHC\AuthNetClient\AuthNetClient';
    }

}
