<?php
/**
 * Created by PhpStorm.
 * User: jefferson.sales
 * Date: 24/01/2018
 * Time: 11:35
 */

namespace src\Models;


use core\MainModel;

/**
 * @ignore
 *
 * Classe de Model do módulo de Estados.
 *
 * Esta classe é acessavel somente no Controller, aqui fica toda a regra de negócios.
 *
 * @author Jefferson Brasilino
 */
class EstadosModel extends MainModel
{

    /**
     * @ignore
     */
    public function __construct($localSelected)
    {
        $this->table = 'estados';
        parent::__construct($localSelected);
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
    public function listEstados($currPage, $filtrosBusca = null)
    {
        if (!$currPage) $currPage = 1;

        $filtros = null;
        if ($filtrosBusca) {
            foreach ($filtrosBusca as $key => $value) {
                if ($key == 'nome') {
                    $filtros .= "LOWER($key) like LOWER('$value%') ";
                }else{
                    $filtros .= "LOWER($key) = LOWER('$value') ";
                }
            }
        }

        $qr = $this->db->select()
            ->from()
            ->where($filtros)
            ->order(['nome asc'])
            ->listPagination(10, $currPage);

        return ["status" => "success", "data" => $qr];
    }

}