<?php

namespace App\helpers;

class ExternalApiHelper {
    public $foo;

    public function __construct($foo)
    {
        $this->foo = $foo;    
    }

    public function foo()
    {
        return $this->foo;
    }

    public static function bar()
    {
        return app(ExternalApiHelper::class)->foo();
    }

    public static function setFoo($foo)
    {
        $externalApi = app(ExternalApiHelper::class);
        $externalApi->foo = $foo;

        return $externalApi;
    }
}