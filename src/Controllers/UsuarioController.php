<?php
/**
 * Created by PhpStorm.
 * User: jefferson.sales
 * Date: 10/01/2018
 * Time: 08:42
 */

namespace src\Controllers;

use core\MainController;

/**
 * Classe de controller do módulo de Usuário.
 * BaseUrl de acesso: http://kenkoapi/Usuario.
 *
 * Para as rotas autenticadas é necessário passar o token gerado no ato do login no cabeçalho da requisição.
 *
 * @author Jefferson Brasilino
 * @package Usuario
 */
class UsuarioController extends MainController {

    /**
     * @ignore
     **/
    public function __construct($dataToken)
    {
        //regra especifica para o login
        if($dataToken){
            parent::__construct($dataToken);
        }else{
            //para fuções que nao precisam de conexao com o banco de dados
            $this->Usuario = new \src\Models\UsuarioModel('');
        }
    }

    /**
     * @ignore
     **/
    public function index($req, $res, $ser, $app){

    }

    /**
     * Autenticação do Usuário(login).
     *
     * url: http://kenkoapi/Usuario
     *
     * - dados a serem enviados:
     *  - usuario
     *      - Nome do usuário.
     *  - senha
     *      - Senha do usuário.
     *  - local
     *      - Chave do local onde o registro do login se encontra.
     *
     * médoto: POST.
     *
     * autenticação: não.
     *
     * Se o login for bem sucedido, será retornado um token de autenticação para ser usado em todas as requisições
     * para a API.
     *
     * @param $req Parametro de requisição (contém os dados do method)
     * @param $res Parametro de resposta nativa da biblioteca de roteamento
     * @param $ser Parametro de serviço nativo da biblioteca de roteamento
     * @param $app Parametro de registro de bibliotecas de terceiros
     * @return array
     * sucesso: <code>['status'=>'success', 'data'=>'...']</code>
     *
     * erro: <code>['status'=>'error', 'message'=>'...']</code>
     */

    //função exclusiva para o App(verifica se o token é valido para realizar o login automatico)
    /**
     * Função exclusiva para o App(verifica se o token é valido para realizar o login automatico).
     *
     * url: http://kenkoapi/Usuario/checkTokenApp
     *
     * - dados a serem enviados:
     *  - token
     *      - Token a ser verificado.
     *
     * médoto: GET.
     *
     * autenticação: não.
     *
     * @param $req Parametro de requisição (contém os dados do method)
     * @param $res Parametro de resposta nativa da biblioteca de roteamento
     * @param $ser Parametro de serviço nativo da biblioteca de roteamento
     * @param $app Parametro de registro de bibliotecas de terceiros
     * @return array
     * sucesso: <code>['status'=>'success', 'data'=>'...']</code>
     *
     * erro: <code>['status'=>'error', 'message'=>'...']</code>
     */
    public function checkTokenApp($req, $res, $ser, $app){
        $params = $req->paramsGet();
        $retorno = $this->Usuario->checkToken($params->token);
        $app->kenkoResponse->response($retorno);
    }

   /* public function addUser($req, $res, $ser, $app){
        $data = $req->paramsPost();
        $retorno = $this->Usuario->addUser($data, $req->method());
        $app->kenkoResponse->response($retorno);
    }*/

   /* public function verificaLogin($req,$res){
        $retorno = $this->model->addUser($req->paramsPost(),$req->method());
      //  $res->json($retorno);
    }*/

}