<?php

use App\Kernel;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

// return function (array $context) {

// if ($_SERVER['APP_DEBUG']) {
//     umask(0000);

//     Debug::enable();
// }

// $_SERVER['SCRIPT_FILENAME'] = __DIR__.'/index.php';

// $kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
// $request = Request::createFromGlobals();
// $response = $kernel->handle($request);
// $response->send();
// $kernel->terminate($request, $response);


return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);


//require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

};


   

