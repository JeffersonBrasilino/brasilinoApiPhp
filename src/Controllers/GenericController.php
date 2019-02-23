<?php
/**
 * Created by PhpStorm.
 * User: jefferson.sales
 * Date: 16/03/2018
 * Time: 14:26
 */

namespace src\Controllers;


use core\MainController;

class GenericController extends MainController
{

    public function processGenericRoute($req, $res, $ser, $app){
        $table = $req->table;
        $action = $req->action;
        $queryStr = $req->paramsGet();
        $identifier = $req->identifier;
        $retorno = [];

        if($req->method() == 'GET'){
            if($action == 'get')
                $retorno = $this->model->getData($table,$action,$queryStr->querystr);
            if($action == 'paginate')
                $retorno = $this->model->listRegistros($identifier, $table,$queryStr->querystr);
        }

        $app->kenkoResponse->response($retorno);
    }

}