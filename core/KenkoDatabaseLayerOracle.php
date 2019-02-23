<?php
/**
 * @author Jefferson Brasilino
 * @package core
 */

namespace core;

require_once "vendor/adodb/adodb-php/adodb-exceptions.inc.php";
require_once "vendor/adodb/adodb-php/adodb-active-record.inc.php";

/**
 * Classe de camada de abstração de banco de dados da api. este método extende a classe ConnectionsDb
 */
class KenkoDatabaseLayerOracle extends ConnectionDB
{
    /**
     * @var string Tabela
     */
    public $table;

    /**
     * @var string Chave Primária da tabela
     */
    public $primaryKey = 'id';

    /**
     * @var string variável que receberá a query dos métodos
     */
    protected $queryStr;

    /**
     * @var \ADODB_Active_Record $activeRecord Instância do ActiveRecord do ADODB
     * @ignore
     */
    private $activeRecord;

    /**
     * @var string Variável que contém a sequencia da tabela
     * @ignore
     */
    private $current_sequence;

    /**
     * @ignore
     */
    public function __construct($config, $table = '')
    {
        $this->table = $table;
        parent::connect($config);

    }

    /**
     * Retorna a Tabela
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Função para a montagem do select
     * @param string $fields Campos da tabela
     * @return KenkoDatabaseLayerOracle
     */
    public function select($fields = '*')
    {
        $this->queryStr = "SELECT {$fields} FROM @table @join @where @like @groupby @order";
        return $this;
    }

    /**
     * Função para a montagem do delete
     * @param string $table Tabela a ter o registro deletado(opcional, pois o padrão da tabela é o da instancia table)
     * @return KenkoDatabaseLayerOracle
     */
    public function delete($table = null)
    {
        $this->queryStr = "delete from @table @where @like";
        (is_null($table)) ? $table = $this->table : false;
        $this->queryStr = str_replace("@table", $table, $this->queryStr);
        return $this;
    }

    /**
     * Função para a montagem da condição FROM da query
     * @param string $table Tabela(opcional, pois o padrão da tabela é o da instancia table)
     * @return KenkoDatabaseLayerOracle
     */
    public function from($table = null)
    {
        if (empty($table))
            $table = (!empty($this->view)) ? $this->view : $this->table;

        $this->queryStr = str_replace('@table', $table, $this->queryStr);
        return $this;
    }

    /**
     * Função para a montagem da condição WHERE da query
     * @param string $where
     * @return KenkoDatabaseLayerOracle
     */
    public function where($where = "1=1")
    {
        (is_null($where)) ? $where = "1=1" : false;
        $this->queryStr = str_replace("@where", "where " . $where, $this->queryStr);
        return $this;
    }
    /**
     * Função para a montagem da condição GROUP BY da query
     * @param array $group ex: ['nome','data_nascimento'] ou 'nome'
     * @return KenkoDatabaseLayerOracle
     */
    public function groupby($group = null)
    {
        if (!is_null($group) and !empty($group)) {
            $group = (is_array($group)) ? "group by " . implode(", ", $group) : "group by " . $group;
        } else {
            $group = "";
        }
        $this->queryStr = str_replace("@groupby", $group, $this->queryStr);
        return $this;
    }

    /**
     * Função para a montagem da condição ORDER BY da query
     * @param array $order ex: ['nome'=>asc,'id'=>'desc']
     * @return KenkoDatabaseLayerOracle
     */
    public function order($order = null)
    {
        if (!is_null($order) and !empty($order)) {
            $order = (is_array($order)) ? "order by " . implode(", ", $order) : "order by " . $order;
        } else {
            $order = "";
        }
        $this->queryStr = str_replace("@order", $order, $this->queryStr);
        return $this;
    }

    /**
     * Função para a montagem da condição LIKE da query
     * @param array $request ex: ["descricao" => "%ouvidoria%"]
     * @return KenkoDatabaseLayerOracle
     */
    public function like($request)
    {
        $this->queryStr = str_replace("@where", "where 1=1", $this->queryStr);
        $like = array();
        $operador = "and";
        if (!empty($request)) {
            $keys = array_keys($request);
            $op = array_shift($keys);
            if ($op == "or" or $op == "and") {
                $request = array_shift($request);
                $operador = $op;
            }
            foreach ($request as $k => $v) {
                $like[] = "upper($k) like upper('$v')";
            }
            $like = "and " . implode(" $operador ", $like);
        }
        $this->queryStr = str_replace("@like", $like, $this->queryStr);
        return $this;
    }

    /**
     * Função para a montagem da condição JOIN da query
     * @param array $request ex: ['left'=>['table_b'=>'table_a.id = table_b.id']]
     * @return KenkoDatabaseLayerOracle
     */
    public function join($request)
    {
        $type = array_keys($request)[0];
        $v = array_values($request)[0];
        $table = array_keys($v)[0];
        $on = array_values($v)[0];
        $join = "$type join $table on $on @join";
        $this->queryStr = str_replace("@join", $join, $this->queryStr);
        return $this;
    }

    /**
     * Função para retornar a query
     * @return string
     */
    public function getQuery()
    {
        return $this->queryStr;
    }

    /**
     * Função para executar presistências customizadas
     * @param array $param ex: [':id'=>'123',...]
     * @return array ['status'=>'success', 'data'=>array]
     */
    public function executePersistence($param = null)
    {
        $retorno = '';
        $this->queryStr = preg_replace("/(@join|@where|@like|@groupby|@order)/", "", $this->queryStr);
        try {
            $this->Execute($this->queryStr, $param);
            $this->Commit();
            $retorno = ['success' => true];
        } catch (\ADODB_Exception $e) {
            $retorno = ['status' => 'error', "data" => ['code' => $e->getCode(), 'message' => $e->getMessage()]];
        }

        return $retorno;
    }

    /**
     * Função para executar e retornar os dados da query SELECT
     * @param array $param ex: ['left'=>['table_b'=>'table_a.id = table_b.id']]
     * @param boolean $prepared se for TRUE irá executar a função preparedQuery caso contrário executará a função Execute(ambas do ConnectionDB)
     * @return array ['status'=>'success', 'data'=> array]
     */
    public function getResults($param = false, $prepared = false)
    {
        $retorno = '';
        $this->queryStr = preg_replace("/(@join|@where|@like|@groupby|@order)/", "", $this->queryStr);
        try {
            if ($prepared === true)
                $execute = $this->PreparedQuery($this->queryStr, $param);
            else
                $execute = $this->Execute($this->queryStr, $param);

            $retorno = $this->getResultsQuery($execute);

        } catch (\ADODB_Exception $e) {
            $retorno = ['status' => 'error', "data" => ['code' => $e->getCode(), 'message' => $e->getMessage()]];
        }

        return $retorno;
    }

    /**
     * Função que processa o retorno dos dados das querys de consulta
     * @param $executeQuery Função da query de consulta
     * @return array
     * @ignore
     */
    private function getResultsQuery($executeQuery)
    {
        $result = array();
        while (!$executeQuery->EOF) {
            array_push($result, $executeQuery->fields);
            $executeQuery->MoveNext();
        }
        return $result;
    }

    /**
     * Função que executa a persistência de dados no banco (insert)
     * @param array $data Array associativo de dados a serem persistidos ex: ['id'=>123,'nome'=>'josh doe']
     * @param string $method Tipo de persistência a ser executada
     * @return array ['status'=>'success', 'data'=> array]
     */
    public function saveData($data, $method = "add")
    {
        $this->activeRecord = new \ADODB_Active_Record($this->table, false, $this->db);

        $retorno = '';
        $this->BeginTrans();
        try {
            $pk = $this->primaryKey;
            if (empty($data->id)) {
                $this->current_sequence = $this->activeRecord->$pk = $this->getSequence();
            } else {
                $this->current_sequence = $this->activeRecord->$pk = $data->$pk;

                if ($method == 'update') {
                    $this->activeRecord->Load($pk . "= :id", array("id" => $data->id));
                }
            }

            foreach ($data as $key => $value) {
                $this->activeRecord->$key = $value;
            }
            $this->activeRecord->Save();
            $this->Commit();
            $retorno = ['status' => "success", "id" => $this->current_sequence];
        } catch (\ADODB_Exception $e) {
            $this->Rollback();
            $retorno = ['status' => 'error', 'message' => $e->getMessage()];
        }

        return $retorno;
    }

    /**
     * Função para pegar a sequencia atual da tabela
     * @param string $name Nome da sequencia da tabela
     * @return integer
     */
    protected function getSequence($name = null)
    {

        if (empty($name))
            $name = $this->table;
        $seq_name = strtoupper("SEQ_" . $name);

        return $this->db->GenID($seq_name);
    }

    /**
     * Função para setar a sequencia
     * @param integer $value Numero atual da sequencia
     * @return void
     * @ignore
     */
    private function setCurrentSequence($value = null)
    {
        $this->current_sequence = $value;
    }

    /**
     * Função para pegar a sequencia
     * @return integer
     */
    public function currentSequence()
    {
        return $this->current_sequence;
    }

    /**
     * Função para paginação
     * @param integer $regPerPage Quantidade de registro por página
     * @param integer $currPage Página a ser buscada
     * @return array
     */
    public function listPagination($regPerPage, $currPage)
    {
        $retorno = '';
        $this->queryStr = preg_replace("/(@join|@where|@like|@groupby|@order)/", "", $this->queryStr);

        if(!is_null($regPerPage)){
            $initReg = ($currPage * $regPerPage) - $regPerPage + 1;
            $endReg = $regPerPage + $initReg - 1;
        }

        try{
            $this->queryStr = "select * from
                            (
                            SELECT rownum NumRow,n.* FROM 
                                ( 
                                  $this->queryStr
                                ) n
                            )
                            where NumRow BETWEEN $initReg AND $endReg";

            $retorno = $this->getResultsQuery($this->Execute($this->queryStr));
        }catch (\ADODB_Exception $e) {
            $retorno = ['status' => 'error', 'message' => $e->getMessage()];
        }

        return $retorno;
    }

}