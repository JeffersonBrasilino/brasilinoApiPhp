<?php
/**
 * Created by PhpStorm.
 * User: jefferson.sales
 * Date: 26/01/2018
 * Time: 16:59
 */

namespace src\Controllers;


use core\MainController;
/**
 * @ignore
 */
class IndexController extends MainController
{

    public function index($req, $res, $ser, $app){
        $res->body("kenko api está funcionando!!!! acesse kenkoapi/docs para visualizar a documentação");
    }

}