<?php
/**
 * Created by PhpStorm.
 * User: jefferson.sales
 * Date: 10/04/2018
 * Time: 11:44
 */

namespace src\Models;


use core\MainModel;

class RunrunItModel extends MainModel
{
    private $pathRost = 'https://secure.runrun.it/api/v1.0';
    private $appKey = 'fb532d263fa1c5b69df447279a4eaa7e';
    private $userToken = 'fWM3M8KpFzU1alYnsW8';

    public function __construct($localSelected)
    {
        parent::__construct($localSelected);
    }

    private function executeRequisition($subPath){

        $ch = curl_init($this->pathRost.$subPath);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "App-Key: ".$this->appKey,
            "User-Token: ".$this->userToken,
            "Content-Type: text/html"
        ]);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $output = curl_exec($ch);
        curl_close($ch);

        return json_decode($output);

    }


    public function getTasks($responsableId=null){
        $queryStr = ($responsableId) ? '?responsible_id=' . $responsableId .'&team_id=55283' : '?team_id=55283';
        return $this->executeRequisition('/tasks'.$queryStr);
    }

    public function getUsers($userId=null){
        $queryStr = ($userId) ? '/'.$userId : '';
        return $this->executeRequisition('/users'.$queryStr);
    }

}