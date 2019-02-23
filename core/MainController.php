<?php
/**
 * Created by PhpStorm.
 * User: jefferson.sales
 * Date: 16/01/2018
 * Time: 09:35
 */

namespace core;

/**
 * Classe de Controller genérico, esta classe deve ser extendida em todos os controllers de modulos.
 * @author Jefferson Brasilino
 * @package core
 */
class MainController
{

    /**
     * @var array $models Recebe os models a serem carregados no controller ex: ['Cidades','Estados']
     */
    protected $model;

    /**
     * @var string $localSelected Recebe a chave do local da base de dados correspondente no arquivo ConnectionDb.json
     */
    protected $localSelected;

    /**
     * @ignore
     **/
    public function __construct($dataToken, $module)
    {
        if($dataToken){
            $this->localSelected = $dataToken->getClaim('local');
        }

        //carrega os models setados no controller
        $this->loadModel($module);
    }

    /**
     * Função interna responsável pelo autoload dos models instanciados no array $models
     * @return void
     * @ignore
     */
    private function loadModel($module=null){
        if(!$this->model)
            $this->model = $module;

            $modelName = '\\src\\Models\\'.$this->model.'Model';
            $this->model = new $modelName($this->localSelected);
    }


    /**
     * Adiciona um registro na Entidade.
     *
     * url: http://kenkoapi/[entidade]
     * - dados a serem enviados:
     *  - array com os dados a serem cadastrados
     *
     * médoto: POST
     *
     * authenticação: sim
     *
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
    public function save($req, $res, $ser, $app)
    {
        $retorno = $this->model->save($req);
        $app->kenkoResponse->response($retorno);
    }

    /**
     * Retorna um Registro ou array de Registros.
     *
     * url: http://kenkoapi/[entidade]/[id]?
     *
     * médoto: GET
     *
     * authenticação: sim
     *
     * ex: http://kenkoapi/Estados retorna o array de estados;
     * ex: http://kenkoapi/Estados/2 retorna o registro de id 2;
     * @param $req Parametro de requisição (contém os dados do method)
     * @param $res Parametro de resposta nativa da biblioteca de roteamento
     * @param $ser Parametro de serviço nativo da biblioteca de roteamento
     * @param $app Parametro de registro de bibliotecas de terceiros
     * @return array
     * sucesso: <code>['status'=>'success', 'data'=>'...']</code>
     *
     * erro: <code>['status'=>'error', 'message'=>'...']</code>
     */
    public function get($req, $res, $ser, $app)
    {
        $retorno = $this->model->get($req);
        $app->kenkoResponse->response($retorno);
    }

    /**
     * Deleta um Registro.
     *
     * url: http://kenkoapi/[entidade]/[id]
     *
     * médoto: DELETE
     *
     * authenticação: sim
     * ex: http://kenkoapi/Estados/2 -> deleta o estado de id 2
     * @param $req Parametro de requisição (contém os dados do method)
     * @param $res Parametro de resposta nativa da biblioteca de roteamento
     * @param $ser Parametro de serviço nativo da biblioteca de roteamento
     * @param $app Parametro de registro de bibliotecas de terceiros
     * @return array
     * sucesso: <code>['status'=>'success', 'data'=>'...']</code>
     *
     * erro: <code>['status'=>'error', 'message'=>'...']</code>
     */
    public function delete($req, $res, $ser, $app)
    {
        $retorno = $this->model->delete($req->id);
        $app->kenkoResponse->response($retorno);
    }
}