<?php
/**
 * Created by PhpStorm.
 * User: jefferson.sales
 * Date: 16/01/2018
 * Time: 09:11
 */

namespace src\Models;

use core\KenkoAuthentication;
use core\MainModel;
/**
 * @ignore
*/
class UsuarioModel extends MainModel
{

    use KenkoAuthentication;

    const token = 'UnimeTeresina';

    public function __construct($localSelected)
    {
        $this->table = 'usuarios';
        $this->validationRules = [
            "login" => [
                "notEmpty",
                "length" => ["min" => 2],
            ]
        ];

        parent::__construct($localSelected);
    }


    /* public function addUser($data, $method)
     {
         $retorno = '';
         $data->senha = sha1(self::token . $data->senha);
         $valid = $this->validate($data);
         if ($valid["status"] == "error") {
             $retorno = $valid;
         } else {
             $retorno = $this->db->saveData($data, $method);
         }
         print_r($retorno);die;

         return $retorno;

    }*/

}