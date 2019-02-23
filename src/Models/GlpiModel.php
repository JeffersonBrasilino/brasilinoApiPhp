<?php
/**
 * Created by PhpStorm.
 * User: jefferson.sales
 * Date: 11/04/2018
 * Time: 11:13
 */

namespace src\Models;


use core\MainModel;

class GlpiModel extends MainModel
{
    public function __construct($localSelected)
    {
        parent::__construct($localSelected);
    }

    public function getTickets($ticketId=null)
    {
        $querystr = ($ticketId) ? ' g.id = '.$ticketId : 'e.id = 7 AND g.status = 2 and YEAR(g.date_creation) = '.date('Y').' order by g.date_creation desc LIMIT 5';
        $res = $this->searchTickets($querystr);
        return $res;
    }

    public function getTicketsByUsers($usersId){
        $queryStr = 'gut.id in('.$usersId.') AND e.id = 7 AND g.status = 2 and YEAR(g.date_creation) = '.date('Y').' order by g.date_creation desc LIMIT 5';
        $res = $this->searchTickets($queryStr);

        return $res;
    }

    public function getUsersGlpi($userId = null){
        $querystr = ($userId) ? 'id = '.$userId : '1=1';
        $res = $this->db->select()
        ->from('glpi_users')
        ->where($querystr)
        ->getResults();

        return $res;
    }

    public function getUsersGlpiByGroups($groupsId){
        $res = $this->db
            ->select("
            distinct glpi_users.*,
            case when ((glpi_users.firstname is null) or (glpi_users.firstname = '')) then glpi_users.realname else glpi_users.firstname end complete_name
            ")
            ->from('glpi_users')
            ->join(['inner'=>['glpi_groups_users'=>'glpi_users.id = glpi_groups_users.users_id']])
            ->join(['inner'=>['glpi_groups'=>'glpi_groups.id = glpi_groups_users.groups_id']])
            ->where('glpi_groups.groups_id in ('.$groupsId.') or glpi_groups.id in ('.$groupsId.') and glpi_users.is_active = 1 and glpi_users.is_deleted <> 1')
            ->getResults();

        return $res;
    }

    private function searchTickets($condition){
        $res = $this->db->select(" g.id, 
        g.name,
        g.date_creation data_abertura,
       g.due_date data_vencimento,
       DATE_FORMAT(g.solvedate,'%d/%m/%Y') data_solucao,
       DATE_FORMAT(g.closedate,'%d/%m/%Y') data_fechamento,
       e.id cd_entidade,
           e.name entidade,
           g.locations_id cd_setor,
           lo.name setor,
           tur.users_id cd_requerente,
           case when ((gur.firstname is null) or (gur.firstname = '')) then gur.realname else gur.firstname end requerente,
           tut.users_id cd_tecnico,
           case when ((gut.firstname is null) or (gut.firstname = '')) then gut.realname else gut.firstname end tecnico,
       g.`status`,
       case g.status
           when 5 then 'solucionado'
           when 6 then 'fechado'
           when 4 then 'pendente'
           when 2 then 'atribuido'
           when 1 then 'novo' end as descricao_status"
        )
            ->from('glpi_tickets g')
            ->join(['inner' => ['glpi_entities e' => 'e.id = g.entities_id']])
            ->join(['left'=>['glpi_tickets_users tur'=>'(tur.tickets_id = g.id and  tur.`type` = 1)']])
            ->join(['left'=>['glpi_tickets_users tut'=>'(tut.tickets_id = g.id and  tut.`type` = 2)']])
            ->join(['left'=>['glpi_users gur'=>'gur.id = tur.users_id']])
            ->join(['left'=>['glpi_users gut'=>'gut.id = tut.users_id']])
            ->join(['left'=>[' glpi_locations lo'=>'lo.id = g.locations_id']])
            ->where($condition)
            ->getResults();

        //calcula a porcentagem
        foreach ($res as $key =>$values){
            $totalTempo = strtotime($values['data_vencimento']) - strtotime($values['data_abertura']);
            $tempoTrabalhado = strtotime('now') - strtotime($values['data_abertura']);
            $porcentagem = ($tempoTrabalhado / $totalTempo) * 100;
            $res[$key]['porcentagem_trabalhada'] = $porcentagem;
        }

        return $res;
    }
}