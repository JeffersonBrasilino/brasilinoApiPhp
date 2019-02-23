<?php
/**
 * Created by PhpStorm.
 * User: jefferson.sales
 * Date: 16/03/2018
 * Time: 14:26
 */

namespace src\Models;


use core\MainModel;

class GenericModel extends MainModel
{
    public function getData($table,$action,$queryStr=''){

        $retorno = [];
        if($action == 'get'){
            $queryStr = ' 1=1 '.str_replace("\"",'',$queryStr);
            $table = str_replace('/','',$table);

            $retorno = $this->db->select()
                ->from($table)
                ->where($queryStr)
                ->getResults();
        }
        return ["status" => "success", "data" => $retorno];

    }

    /**
     * Função que lista(com paginação) os estados.
     *
     * @param $currPage Pagina a ser buscado os registros.
     * @param $filtrosBusca Filtos da busca.
     * @return array
     * sucesso: <code>['status'=>'success', 'data'=>'...']</code>
     *
     * erro: <code>['status'=>'error', 'message'=>'...']</code>
     */
    public function listRegistros($currPage, $table, $queryStr)
    {
        if (!$currPage) $currPage = 1;

        $queryStr = ' 1=1 '.str_replace("\"",'',$queryStr);
        $table = str_replace('/','',$table);

        $qr = $this->db->select()
            ->from($table)
            ->where($queryStr)
            ->order(['name asc'])
            ->listPagination(10, $currPage);

        return ["status" => "success", "data" => $qr];
    }

}