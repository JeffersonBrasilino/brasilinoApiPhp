<?php
/**
 * @author Jefferson Brasilino
 * @package core
 */

namespace core;

require_once 'vendor/adodb/adodb-php/adodb.inc.php';
require_once "vendor/adodb/adodb-php/adodb-exceptions.inc.php";

/**
 * Classe de conexao e persistencia dos dados de forma primitiva com banco de dados, usa o adodb para a conexão.
 */
class ConnectionDB
{
    /** @var string Identificador da base de dados conforme o arquivo connectionsDb.json */
    protected $local;
    /** @var ADODB Instância do ADODB */
    protected $db;

    /**
     * Seta o identificador da base de dados
     * @param string $local Local da base de dados
     * @return void
     */
    protected function setLocal($local)
    {
        $this->local = $local;
    }

    /**
     * Retorna o identificador do local.
     * @return string
     */
    public function getLocal()
    {
        return $this->local;
    }

    /**
     * Faz a conexão com o banco de dados.
     * @return \ADOConnection
     */
    protected function connect($config)
    {
        try {

            if($config->driver == 'oci8')
                $this->db = $this->connectOracle($config);

            elseif($config->driver == 'mysqli')
                $this->db = $this->connectMysql($config);

            $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
            \ADODB_Active_Record::SetDatabaseAdapter($this->db);

        } catch (\ADODB_Exception $e) {
            echo $e;
        }
    }


    private function connectOracle($config){
        $conection = ADONewConnection($config->driver);
        if ($config->configs) {
            foreach ($config->configs as $key => $value) {
                $chave = (string)$key;
                $conection->$chave = $value;
            }
        }
        $conection->PConnect($config->tns, $config->user, $config->password);

        return $conection;
    }

    private function connectMysql($config){
        $conn = ADONewConnection($config->driver);
        $conn->PConnect($config->host,$config->user,$config->password,$config->database);
        $conn->Execute("set names 'utf8'");
        return $conn;
    }

    /**
     * Debugador do ADODB
     * @param boolean $param
     * @return void
     */
    public function debug($param = false)
    {
        $this->db->debug = $param;
    }

    /**
     * Executa a query.
     * @param string $txt_sql Query sql
     * @param array $params Array associativo de valores para a query(caso queira usar o SqlInjection)
     */
    public function Execute($txt_sql, $params = false)
    {
        return $this->db->query($txt_sql, $params);
    }

    /**
     * Prepara a query e logo Após a executa.
     * @param string $txt_sql Query sql
     * @param array $params Array associativo de valores para a query(caso queira usar o SqlInjection)
     */
    public function PreparedQuery($txt_sql, $params)
    {
        $stmt = $this->db->Prepare($txt_sql);
        return $this->db->Execute($stmt, $params);
    }

    /**
     * Inicia a Transação.
     * @param string $txt_sql Query sql
     *@param array $params Array associativo de valores para a query(caso queira usar o SqlInjection)
     */
    public function BeginTrans()
    {
        return $this->db->StartTrans();
    }

    /**
     * Envia a transação para o banco de dados.
     */
    public function Commit()
    {
        return $this->db->CompleteTrans();
    }

    /**
     * Retorna o erro Caso o Commit não for bem sucedido.
     */
    public function Rollback()
    {
        return $this->db->FailTrans();
    }

}