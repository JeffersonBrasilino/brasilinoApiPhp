<?php
/**
 * Created by PhpStorm.
 * User: jefferson.sales
 * Date: 16/03/2018
 * Time: 16:01
 */

namespace src\Models;


use core\KenkoAuthentication;
use core\MainModel;

class LoginModel extends MainModel
{
    use KenkoAuthentication;
    const token = 'UnimeTeresina';

    public function loginUniweb($user, $password, $local)
    {
        $passwordEncripted = sha1(self::token . $password);

        $retorno = [];
        $usuario = $this->db
            ->select('id')
            ->from('usuarios')
            ->where("login = :login AND senha = :senha and status = 1")
            ->getResults(['login' => $user, 'senha' => $passwordEncripted]);

        if (!empty($usuario)) {
           $retorno = [
                'status' => 'success',
                'data' => (string)$this->generateToken($user, $passwordEncripted, $local)
            ];
        } else {
            $retorno = ['status' => 'error', 'data' => 'Usuario ou senha incorretos.'];
        }

        return $retorno;

    }

    public function loginGlpi($user, $password, $local)
    {

        $retorno = [];
        $users = $this->db
            ->select('password')
            ->from('glpi_users')
            ->where("is_active = 1 and name = ? ")
            ->getResults([$user]);

        if ($users[0]['password']) {
            if (!password_verify($password, $users[0]['password']))
                $retorno = ['status' => 'error', 'data' => 'Usuario ou senha incorretos.'];
            else
                $retorno = [
                    'status' => 'success',
                    'data' => (string)$this->generateToken($user, $users[0]['password'], $local)
                ];
        }else{
            $retorno = ['status' => 'error', 'data' => 'Usuario ou senha incorretos.'];
        }

       return $retorno;
    }


    public function checkToken($token = null)
    {
        $retorno = true;
        if (!empty($token)) {
            $retorno = $this->validateToken($token);
        } else {
            $retorno = false;
        }
        return ['status' => 'success', 'data' => $retorno];
    }
}