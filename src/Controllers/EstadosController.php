<?php

namespace src\Controllers;


use core\MainController;

/**
 * Classe de controller do módulo de Estados.
 * BaseUrl de acesso: http://kenkoapi/Estados.
 *
 * Para as rotas autenticadas é necessário passar o token gerado no ato do login no cabeçalho da requisição.
 *
 * @author Jefferson Brasilino
 * @package Estados
 */
class EstadosController extends MainController
{

    /**
     * @ignore
     */
    public function index($req, $res, $ser, $app)
    {
        if ($req->method() == 'POST' || $req->method() == 'PUT') {
            parent::save($req, $res, $ser, $app);
        } elseif ($req->method() == 'GET') {
            parent::get($req, $res, $ser, $app);
        } elseif ($req->method() == 'DELETE') {
            parent::delete($req, $res, $ser, $app);
        }
    }


    /**
     * Listagem de estados com paginação(list).
     *
     * url: http://kenkoapi/Estados/list/[paginaAtual]?
     *
     * médoto: GET
     *
     * authenticação: sim
     * @param $req Parametro de requisição (contém os dados do method)
     * @param $res Parametro de resposta nativa da biblioteca de roteamento
     * @param $ser Parametro de serviço nativo da biblioteca de roteamento
     * @param $app Parametro de registro de bibliotecas de terceiros
     * @return array
     * sucesso: <code>['status'=>'success', 'data'=>'...']</code>
     *
     * erro: <code>['status'=>'error', 'message'=>'...']</code>
     */
    public function listEstados($req, $res, $ser, $app)
    {
        $filtros = $req->paramsGet();
        $retorno = $this->Estados->listEstados($req->page, $filtros);
        $app->kenkoResponse->response($retorno);
    }
}