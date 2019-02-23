<?php
/**
 * Created by PhpStorm.
 * User: jefferson.sales
 * Date: 02/02/2018
 * Time: 16:26
 * classe responsável pelos retornos especificos da api (pois a bendita lib de rotas só disponibiliza o retorno em json)
 */

namespace core;


use \Klein\Response;
use Spatie\ArrayToXml\ArrayToXml;

/**
 * Classe para os retornos customizados da api (ex: json,xml)
 * @author Jefferson Brasilino
 * @package core
 */

class KenkoResponse extends Response
{
    /** @var string $responseType Tipo do retorno (setado no arquivo de rotas do modulo, padrão é json) */
    public $responseType = 'json';

    /**
     * Função para retorno da requisição em XML
     * @param array $content array Array de dados a serem retornados
     * @return Xml
     */
    public function xml($content)
    {
        $convert = ArrayToXml::convert($content);

        $this->header("Content-Type", 'text/xml');
        $this->body($convert);
        $this->send();
        return $this;
    }

    /**
     * Função para retorno da requisição em JSON
     * @param array $object array Array de dados a serem retornados
     * @return Json
     */
    public function json($object, $jsonp_prefix = null)
    {
        $this->body('');
        $this->noCache();

        $json = json_encode($object);

        if (null !== $jsonp_prefix) {
            // Should ideally be application/json-p once adopted
            $this->header('Content-Type', 'text/javascript');
            $this->body("$jsonp_prefix($json);");
        } else {
            $this->header('Content-Type', 'application/json');
            $this->body($json);
        }

        $this->send();

        return $this;
    }

    /**
     * Função de resposta da requisição, ela é responsável por chamar o tipo de conversão especificado na rota.
     * @param array $object Array dos dados a serem retornados
     * @return Xml
     */
    public function response($object){
        $typeResponse = $this->responseType;
        $this->$typeResponse($object);
    }

}