<?php
/**
 * Created by PhpStorm.
 * User: jefferson.sales
 * Date: 15/01/2018
 * Time: 17:07
 */

namespace core;

class App
{
    use ProcessRoute;

    public function start()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Credentials: true");
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE');
        header('Access-Control-Max-Age: 1000');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

        $this->dispatch();
    }

}