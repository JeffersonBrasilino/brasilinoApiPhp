<?php
/**
 * Created by PhpStorm.
 * User: jefferson.sales
 * Date: 11/04/2018
 * Time: 11:13
 */

namespace src\Controllers;


use core\MainController;

class GlpiController extends MainController
{
    /**
     * @api {get} /Glpi/calls/:id Busca os Chamados
     * @apiVersion 0.2.0
     * @apiName getCalls
     * @apiGroup Glpi
     * @apiParam {Number} id Identificação do chamado(opcional).
     * @apiExample Exemplo de Uso:
     * curl -i http://kenkoapi/Glpi/calls/4711
     *
     * @apiSuccessExample {json} Resposta de sucesso:
     *     HTTP/1.1 200 OK
     *     {
     *      "id": "49133",
     *       "name": "ATIVAR USUARIOS SAW UNIMED E INTERMED",
     *       "data_abertura": "2018-04-12 16:02:40",
     *       "data_vencimento": "2018-04-13 16:02:40",
     *       "data_solucao": null,
     *       "data_fechamento": null,
     *       "cd_entidade": "7",
     *       "entidade": "Unimed Teresina",
     *       "cd_setor": "120",
     *       "setor": "Produção Médica",
     *       "cd_requerente": "79",
     *       "requerente": "Salete Soares",
     *       "cd_tecnico": "6",
     *       "tecnico": "Kléssio Grenne-suporte",
     *       "status": "4",
     *       "descricao_status": "pendente",
     *       "porcentagem_trabalhada": 89.2488425925926
     *     }
     */
    public function getTickets($req, $res, $ser, $app)
    {
        $res = $this->model->getTickets($req->id);
        $app->kenkoResponse->response($res);
    }

    /**
     * @api {get} /Glpi/users/:id Busca os Usuários.
     * @apiVersion 0.2.0
     * @apiName getUsers
     * @apiGroup Glpi
     * @apiParam {Number} id Identificação do Usuário(opcional).
     * @apiExample Exemplo de Uso:
     * curl -i http://kenkoapi/Glpi/users/6
     *
     * @apiSuccessExample {json} Resposta de sucesso:
     *     HTTP/1.1 200 OK
     *     {
     *       "highcontrast_css": "0",
     * "date_creation": null,
     * "plannings": null,
     * "set_default_requester": null,
     * "lock_directunlock_notification": null,
     * "lock_autolock_mode": null,
     * "id": "35",
     * "name": "Suely",
     * "password": "4dea6bb3b85aa4bc23d6c7c4f932ecba37a72c9e",
     * "phone": "",
     * "phone2": "",
     * "mobile": "",
     * "realname": "Suely Macedo",
     * "firstname": "",
     * "locations_id": "15",
     * "language": null,
     * "use_mode": "0",
     * "list_limit": null,
     * "is_active": "0",
     * "comment": "",
     * "auths_id": "0",
     * "authtype": "1",
     * "last_login": "2011-12-19 15:09:51",
     * "date_mod": "2016-07-18 08:28:48",
     * "date_sync": null,
     * "is_deleted": "0",
     * "profiles_id": "0",
     * "entities_id": "0",
     * "usertitles_id": "0",
     * "usercategories_id": "2",
     * "date_format": null,
     * "number_format": null,
     * "names_format": null,
     * "csv_delimiter": null,
     * "is_ids_visible": null,
     * "use_flat_dropdowntree": null,
     * "show_jobs_at_login": null,
     * "priority_1": null,
     * "priority_2": null,
     * "priority_3": null,
     * "priority_4": null,
     * "priority_5": null,
     * "priority_6": null,
     * "followup_private": null,
     * "task_private": null,
     * "default_requesttypes_id": null,
     * "password_forget_token": "",
     * "password_forget_token_date": null,
     * "user_dn": null,
     * "registration_number": "",
     * "show_count_on_tabs": null,
     * "refresh_ticket_list": null,
     * "set_default_tech": null,
     * "personal_token": null,
     * "personal_token_date": null,
     * "display_count_on_home": null,
     * "notification_to_myself": null,
     * "duedateok_color": null,
     * "duedatewarning_color": null,
     * "duedatecritical_color": null,
     * "duedatewarning_less": null,
     * "duedatecritical_less": null,
     * "duedatewarning_unit": null,
     * "duedatecritical_unit": null,
     * "display_options": null,
     * "is_deleted_ldap": "0",
     * "pdffont": null,
     * "picture": null,
     * "begin_date": null,
     * "end_date": null,
     * "keep_devices_when_purging_item": null,
     * "privatebookmarkorder": null,
     * "backcreated": null,
     * "task_state": null,
     * "palette": "greenflat",
     * "layout": null,
     * "ticket_timeline": null,
     * "ticket_timeline_keep_replaced_tabs": null
     *     }
     */
    public function getUsersGlpi($req, $res, $ser, $app)
    {
        $res = $this->model->getUsersGlpi($req->id);
        $app->kenkoResponse->response($res);
    }

    /**
     * @api {get} /Glpi/groups/:groupId/users Busca os Usuários pelo Grupo
     * @apiVersion 0.2.0
     * @apiName getUsersByGroup
     * @apiGroup Glpi
     * @apiParam {Number} groupId Identificação do Grupo.
     * @apiExample Exemplo de Uso:
     * curl -i http://kenkoapi/Glpi/groups/35/users
     *
     * @apiSuccessExample {json} Resposta de sucesso:
     *     HTTP/1.1 200 OK
     *     {
     *       "highcontrast_css": "0",
     * "date_creation": null,
     * "plannings": null,
     * "set_default_requester": null,
     * "lock_directunlock_notification": null,
     * "lock_autolock_mode": null,
     * "id": "35",
     * "name": "Suely",
     * "password": "4dea6bb3b85aa4bc23d6c7c4f932ecba37a72c9e",
     * "phone": "",
     * "phone2": "",
     * "mobile": "",
     * "realname": "Suely Macedo",
     * "firstname": "",
     * "locations_id": "15",
     * "language": null,
     * "use_mode": "0",
     * "list_limit": null,
     * "is_active": "0",
     * "comment": "",
     * "auths_id": "0",
     * "authtype": "1",
     * "last_login": "2011-12-19 15:09:51",
     * "date_mod": "2016-07-18 08:28:48",
     * "date_sync": null,
     * "is_deleted": "0",
     * "profiles_id": "0",
     * "entities_id": "0",
     * "usertitles_id": "0",
     * "usercategories_id": "2",
     * "date_format": null,
     * "number_format": null,
     * "names_format": null,
     * "csv_delimiter": null,
     * "is_ids_visible": null,
     * "use_flat_dropdowntree": null,
     * "show_jobs_at_login": null,
     * "priority_1": null,
     * "priority_2": null,
     * "priority_3": null,
     * "priority_4": null,
     * "priority_5": null,
     * "priority_6": null,
     * "followup_private": null,
     * "task_private": null,
     * "default_requesttypes_id": null,
     * "password_forget_token": "",
     * "password_forget_token_date": null,
     * "user_dn": null,
     * "registration_number": "",
     * "show_count_on_tabs": null,
     * "refresh_ticket_list": null,
     * "set_default_tech": null,
     * "personal_token": null,
     * "personal_token_date": null,
     * "display_count_on_home": null,
     * "notification_to_myself": null,
     * "duedateok_color": null,
     * "duedatewarning_color": null,
     * "duedatecritical_color": null,
     * "duedatewarning_less": null,
     * "duedatecritical_less": null,
     * "duedatewarning_unit": null,
     * "duedatecritical_unit": null,
     * "display_options": null,
     * "is_deleted_ldap": "0",
     * "pdffont": null,
     * "picture": null,
     * "begin_date": null,
     * "end_date": null,
     * "keep_devices_when_purging_item": null,
     * "privatebookmarkorder": null,
     * "backcreated": null,
     * "task_state": null,
     * "palette": "greenflat",
     * "layout": null,
     * "ticket_timeline": null,
     * "ticket_timeline_keep_replaced_tabs": null
     *     }
     */
    public function getUsersByGroup($req, $res, $ser, $app)
    {
        $res = $this->model->getUsersGlpiByGroups($req->groupId);
        $app->kenkoResponse->response($res);
    }


    /**
     * @api {get} /Glpi/users/:usersId/calls Busca os Chamados por Usuário.
     * @apiVersion 0.2.0
     * @apiName getCallsByUser
     * @apiGroup Glpi
     * @apiParam {Number} usersId Identificação do Usuário.
     * @apiExample Exemplo de Uso:
     * curl -i http://kenkoapi/Glpi/users/123/calls
     *
     * @apiSuccessExample {json} Resposta de sucesso:
     *     HTTP/1.1 200 OK
     *     {
     *      "id": "49133",
     *       "name": "ATIVAR USUARIOS SAW UNIMED E INTERMED",
     *       "data_abertura": "2018-04-12 16:02:40",
     *       "data_vencimento": "2018-04-13 16:02:40",
     *       "data_solucao": null,
     *       "data_fechamento": null,
     *       "cd_entidade": "7",
     *       "entidade": "Unimed Teresina",
     *       "cd_setor": "120",
     *       "setor": "Produção Médica",
     *       "cd_requerente": "79",
     *       "requerente": "Salete Soares",
     *       "cd_tecnico": "6",
     *       "tecnico": "Kléssio Grenne-suporte",
     *       "status": "4",
     *       "descricao_status": "pendente",
     *       "porcentagem_trabalhada": 89.2488425925926
     *     }
     */
    public function getTicketsByUser($req, $res, $ser, $app)
    {
        $res = $this->model->getTicketsByUsers($req->usersId);
        $app->kenkoResponse->response($res);
    }
}