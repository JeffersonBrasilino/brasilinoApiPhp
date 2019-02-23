<?php
/**
 * Created by PhpStorm.
 * User: jefferson.sales
 * Date: 16/01/2018
 * Time: 11:21
 */

namespace core;
require_once "vendor/adodb/adodb-php/adodb-exceptions.inc.php";
require_once "vendor/adodb/adodb-php/adodb-active-record.inc.php";
/**
 * Classe de Model genérico, esta classe deve ser extendida em todos os models de modulos.
 * @author Jefferson Brasilino
 * @package core
 */
class MainModel
{
    /** @var KenkoDatabaseLayerOracle $db Recebe a camada de database layer da api, ela é a responsável por todas as instancias de requisições*/
    public $db;
    /** @var string $table Nome da tabela do model*/
    public $table = '';

    use KenkoValidator;

    /**
     * @ignore
     **/
    public function __construct($localSelected)
    {
        if ($localSelected)
            $this->getConnection($localSelected);
    }


    private function getConnection($localSelected){
        $pathConfig = 'pub/ConnectionsDb.json';

        if (!file_exists($pathConfig))
            throw new \Error("Arquivo de configuração do banco de dados 'ConnectionsDb.json' não encontrado. Verifique se existe na pasta 'pub' ");
        elseif (pathinfo($pathConfig, PATHINFO_EXTENSION) != 'json')
            throw new \Error("Arquivo de configuração do banco de dados inválido. Use somente arquivo JSON");

        $configFile = json_decode(file_get_contents($pathConfig));
        $config = $configFile->$localSelected;

        if (!$config)
            throw new \Error("configuracao para o banco {$localSelected} nao existente.");

        if($config->driver == 'mysqli')
            $this->db = new KenkoDatabaseLayerMysql($config, $this->table);
        if ($config->driver == 'oci8')
            $this->db = new KenkoDatabaseLayerOracle($config, $this->table);

    }

    /**
     * Função que adiciona/edita uma Entidade.
     *
     * @param $req Parametro de requisição (contém os dados do method)
     * @return array
     * sucesso: <code>['status'=>'success', 'data'=>'...']</code>
     *
     * erro: <code>['status'=>'error', 'message'=>'...']</code>
     */
    public function save($req){
        $dados = '';
        $action = '';
        if ($req->method() == 'POST') {
            $dados = $req->paramsPost();
            $action = 'add';
        } elseif ($req->method() == 'PUT') {
            $action = "update";
            $dados = $req->paramsGet();
            $dados->id = $req->id;
        }
        $retorno = $this->db->saveData($dados, $action);
        return $retorno;

    }

    /**
     * Função que deleta um registro da Entidade.
     *
     * @param $id Id do registro a ser excluído
     * @return array
     * sucesso: <code>['status'=>'success', 'data'=>'...']</code>
     *
     * erro: <code>['status'=>'error', 'message'=>'...']</code>
     */
    public function delete($id)
    {
        $qr = $this->db
            ->delete()
            ->from()
            ->where('id = :id')
            ->executePersistence(['id' => $id]);

        return $qr;
    }

    /**
     * Função que busca um ou mais Regustros da Entidade.
     *
     * @param $req Parametro de requisição (contém os dados do method)
     * @return array
     * sucesso: <code>['status'=>'success', 'data'=>'...']</code>
     *
     * erro: <code>['status'=>'error', 'message'=>'...']</code>
     */
    public function get($req)
    {
        $params = $req->params();
        $valores = array();
        $where = '1=1 ';
        foreach ($params as $key => $value) {
            if (!is_int($key)) {
                $valores[$key] = $value;
                $where .= "AND {$key} = :{$key} ";
            }
        }

        $qr = $this->db->select()
            ->from()
            ->where($where)
            ->order(['id desc'])
            ->getResults($valores);
        return ["status" => "success", "data" => $qr];
    }

}