<?php
/**
 * Created by PhpStorm.
 * User: jefferson.sales
 * Date: 16/03/2018
 * Time: 16:00
 */

namespace src\Controllers;

class LoginController
{

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
    public function login($req, $res, $ser, $app)
    {
        $data = $req->paramsPost();
        $retorno = '';
        $model = new \src\Models\LoginModel($data->local);

        if ($data->local == 'uniwebProd')
            $retorno = $model->loginUniweb($data->usuario, $data->senha, $data->local);

        if ($data->local == 'glpi')
            $retorno = $model->loginGlpi($data->usuario, $data->senha, $data->local);

        $app->kenkoResponse->response($retorno);
    }

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
    public function checkTokenApp($req, $res, $ser, $app)
    {
        $params = $req->paramsGet();
        $retorno = $this->model->checkToken($params->token);
        $app->kenkoResponse->response($retorno);
    }
}