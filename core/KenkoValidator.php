<?php
/**
 * Created by PhpStorm.
 * User: jefferson.sales
 * Date: 23/01/2018
 * Time: 09:12
 */

namespace core;

use Respect\Validation\Validator as validator;
use Respect\Validation\Exceptions\NestedValidationException;
/**
 * Trait responável pela validação dos campos da da requisição.
 * @author Jefferson Brasilino
 * @package core
 */
trait KenkoValidator
{
    /**
     * @var array $validationRules Array associativo de campos e regras para validação
     */
    protected $validationRules;

    /**
     * @var object $dataValidation Responsável por receber o objeto da validação
     * @ignore
     */
    private $dataValidation;

    /**
     * @var array $messages Mensagens customizadas de erro de validação
     * @ignore
     */
    private static $messages = [
        "attribute" => "o campo '{{name}}' não esta presente para validação.",
        "numeric" => "o campo '{{name}}' não é numero.",
        "cpf" => "o campo '{{name}}' deve conter um cpf válido.",
        "stringType" => "o campo '{{name}}' deve ser do tipo string.",
        "nullType" => "o campo '{{name}}' deve que ser nulo.",
        "alpha" => "o campo '{{name}}' deve conter apenas letras.",
        "notEmpty" => "o campo '{{name}}' não deve ser vazio ou nulo.",
        "cnh" => "o campo '{{name}}' deve conter uma cnh válida.",
        "length" => "o campo '{{name}}' está com o tamanho incorreto."

    ];

    /**
     * Função que valida os campos.
     * @param array $dataObject
     * @return array
     */
    function validate($dataObject)
    {
        $retorno = '';
        if (empty($this->validationRules)) {
            $retorno = array('status' => 'error', 'message' => 'para a validacao funcionar deve ser configurado a variavel validationRules.');

        } else {
            try {
                self::processData($dataObject);
                self::processRules();
                $retorno = ["status" =>'success'];
            } catch (NestedValidationException $e) {
                $e->findMessages(self::$messages);
                $retorno = ["status" => 'error', 'message'=>$e->getMessages()];
            }
        }
        return $retorno;
    }

    /**
     * Função interna responsável pela conversão dos dados.
     * @param array $data array Array de dados a serem retornados
     * @return void
     * @ignore
     */
    private function processData($data){
        $this->dataValidation = new \stdClass();
        foreach ($data as $attr=>$value){
            $this->dataValidation->$attr = $value;
        }
    }

    /**
     * Função interna que é responsável por processar as regras instanciadas no $validationRules
     * @return void
     * @ignore
     */
    private function processRules()
    {
        $instance = new validator();
        foreach ($this->validationRules as $field => $rule) {
            $valid = new validator();
            foreach ($rule as $key => $value) {
                if ($value && is_array($value)) {
                    if (!isset($value["min"]))
                        $value["min"] = null;

                    if (!isset($value["max"]))
                        $value["max"] = null;

                    $valid->$key($value["min"], $value["max"]);
                } else {
                    $valid->$value();
                }
            }
            $instance->attribute($field, $valid);
        }
        $instance->assert($this->dataValidation);
    }

    /**
     * Retorna o array de mensagens customizadas
     * @return array
     */
    public function getMessagesRules()
    {
        return $this::$messages;
    }

    /**
     * Retorna o OBJETO de campos a serem validados
     * @return object
     */
    public function getObjectValidate()
    {
        return $this->dataValidation;
    }

    /**
     * Retorna o ARRAY de campos a serem validados
     * @return array
     */
    public function getValidationRules()
    {
        return $this->validationRules;
    }

}