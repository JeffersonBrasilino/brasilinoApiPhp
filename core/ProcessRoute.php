<?php

/**
 * @author Jefferson Brasilino
 * @package core
 * @example  www.google.com
 */

namespace core;

use Klein\Klein;

/**
 * Trait que processa as rotas da api. o arquivo de rotas PRINCIPAL deve ser un json e
 * conter nome do modulo e caminho para as todas do modulo(json)
 */

trait ProcessRoute
{
    /** @var string Variável que receberá o caminho das subrotas */
    protected $fileRouter;
    /** @var  string  Variável que receberá o modulo da requisição processado pela function getActualyModule */
    protected $module;
    /** @var Klein  Instancia da lib de rotas */
    protected $routes;

    use KenkoAuthentication;

    public function __construct()
    {
        $this->routes = new Klein();
    }

    /**
     * Função que seta o arquivo de mapeamento de rotas principal (JSON)
     * @param string $pathFile Caminho do arquivo json
     * @return null
     */
    public function setRoutesFile($pathFile)
    {
        try {
            if (!file_exists($pathFile))
                throw new \Error('Arquivo de rotas não encontrado.');
            elseif (pathinfo($pathFile, PATHINFO_EXTENSION) != 'json')
                throw new \Error('Arquivo de rotas inválido. Use somente arquivos JSON');

            $this->fileRouter = json_decode(file_get_contents($pathFile));
        } catch (\Error $e) {
            echo $e;
        }
    }

    /**
     * Função que retorna os dados do arquivo json de rotas principal
     * @return array
     */
    public function getRoutesFile()
    {
        return $this->fileRouter;
    }

    /**
     * Função responsável pela quebra da rota atual para o carregamento das rotas sob demanda
     * @return void
     */
    private function getActualyModule()
    {
        $module = explode('/', $_SERVER['REQUEST_URI']);
        $this->module = $module[1];
    }


    /**
     * Função que processa as rotas da requisição atual
     * @param string $module Modulo da requisição processado pela function getActualyModule
     * @param object $fileRouter Objeto contendo o caminho de cada arquivo de subrotas de cada modulo
     * @return void
     */
    private function processRouteModule($module, $fileRouter)
    {

        try {
            $pathSubRoutes = $fileRouter->$module;

            /* if (!file_exists($pathSubRoutes))
                 throw new \Error("Arquivo de rotas {$module} não encontrado.");
             elseif (pathinfo($pathSubRoutes, PATHINFO_EXTENSION) != 'json')
                 throw new \Error("Arquivo de rotas {$module} inválido. Use somente arquivos JSON");*/

            $subRoutes = json_decode(file_get_contents($pathSubRoutes));
            $this->routes->with('/' . $module, function () use ($module, $subRoutes) {
                $this->processSubRoutesModule($subRoutes, $module);
            });

        } catch (\Error $e) {
            echo $e;
        }
    }

    /**
     * Função que processa as sub-rotas do modulo
     * @param object $subRoutes Objeto das subrotas de cada modulo
     * @param string $module Modulo da requisição processado pela function getActualyModule
     * @return void
     */
    private function processSubRoutesModule($subRoutes, $module)
    {
        //entrypoint da api
        if (!$module) $module = 'Index';

        foreach ($subRoutes as $function => $routeParams) {
            $this->routes->respond($routeParams->method, $routeParams->route, function ($req, $res, $ser, $app) use ($module, $function, $routeParams) {

                //registra a classe de respostas personalizadas da api nas rotas
                $app->register('kenkoResponse', function () {
                    return new KenkoResponse();
                });

                //seta o tipo de resposta
                if ($routeParams->responseType)
                    $app->kenkoResponse->responseType = $routeParams->responseType;

                $tokenHttp = $req->headers()->Authorization;
                //verifica se o token é valido
                if ($routeParams->authenticated == true)
                    $this->verifyToken($tokenHttp);

                //caso o token seja valido, ele é feito a descriptografia
                $dataToken = null;
                if ($tokenHttp && $routeParams->authenticated == true) {
                    $dataToken = $this->parseToken($tokenHttp);
                }

                //instancia o objeto do controller do modulo
                $controllerName = '\\src\\Controllers\\' . $module . 'Controller';
                $controller = new $controllerName($dataToken,$module);

                //chama a função do modulo
                $controller->$function($req, $res, $ser, $app);

            });
        }
    }

    /**
     * Processa o retorno de erro das rotas
     */
    private function processRouteError()
    {
        $this->routes->onHttpError(function ($code, $router) {
            $router->response()->json(['status' => 'error', 'code' => $code]);
        });
    }

    /**
     * Verifica o token pela rota
     * @param string $token Token para verificação
     */
    private function verifyToken($token)
    {
        if (!$this->validateToken($token))
            $this->routes->abort(401);
    }

    /**
     * Função responsável por descriptogravar o token
     * @param string $token Token para descriptografar
     */
    private function parseToken($token)
    {
        return (object)$this->decriptToken($token);
    }

    /**
     * Inicia o servidor de rotas
     * @return void
     */
    public function dispatch()
    {
        $this->processRouteError();
        $this->getActualyModule();
        $this->processRouteModule($this->module, $this->fileRouter);
        $this->routes->dispatch();
    }

}