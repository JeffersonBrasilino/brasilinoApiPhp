<?php
/**
 * @author Jefferson Brasilino
 * @package core
 */

namespace core;
/**
 * Trait que é responsável pela authenticacção da api (gerar token, validar token, descriptografar token).
*/
trait KenkoAuthentication
{

    //chave para criptografia e descriptografia do token
    /** @var string Chave de criptografia do token
     * @ignore
     */
    private $kenkoApiToken = '04AD0BE772DD8422F84F8381A0EA76F312932A01B4773E54C4B71EB92714B0CA';

    /** @var string Provedor do token
     * @ignore
     */
    private $issuer = 'KenkoApi';

    /**
     * Gera o token.
     * @param string $user Usuário
     * @param string $password Senha
     * @param string $local Local da base de dados
     * @return string
     */
    protected function generateToken($user, $password, $local = 'unimed')
    {
        $token = (new \Lcobucci\JWT\Builder())
            ->setIssuer($this->issuer)
            ->setIssuedAt(time())
            ->setExpiration(time() + 28800)
            ->set('user', $user)
            ->set('password', $password)
            ->set('local', $local)
            ->sign((new \Lcobucci\JWT\Signer\Hmac\Sha256()), $this->kenkoApiToken)
            ->getToken();

        return $token;
    }

    /**
     * Valida o token.
     * @param string $token Token a ser validado
     * @return boolean
     */
    public function validateToken($token)
    {
        $retorno = true;
        try{
            if($token){
                $token = str_replace("Bearer ",'',$token);

                $dataToken = (new \Lcobucci\JWT\Parser())->parse($token);
                $validation = new \Lcobucci\JWT\ValidationData();
                $validation->setIssuer($this->issuer);
                if (!$dataToken->validate($validation) || !$dataToken->verify((new \Lcobucci\JWT\Signer\Hmac\Sha256()), $this->kenkoApiToken))
                    $retorno = false;
            }else{
                $retorno = false;
            }

        }catch (\Exception $e){
            $retorno = false;
        }

        return $retorno;
    }

    /**
     * Gera o token.
     * @param string $token Token a ser descriptografado
     * @return \Lcobucci\JWT\Parser
     */
    public function decriptToken($token){
        $token = str_replace("Bearer ",'',$token);
        $dataToken = new \Lcobucci\JWT\Parser();
        return $dataToken->parse($token);
    }

}