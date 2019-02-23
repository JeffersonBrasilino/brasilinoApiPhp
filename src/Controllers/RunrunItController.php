<?php
/**
 * Created by PhpStorm.
 * User: jefferson.sales
 * Date: 10/04/2018
 * Time: 11:43
 */

namespace src\Controllers;


use core\MainController;

class RunrunItController extends MainController
{

    /**
     * @api {get} /RunrunIt/tasks/:id Busca as Tarefas.
     * @apiVersion 0.2.0
     * @apiName getTasksRunrunIt
     * @apiGroup RunrunIt
     * @apiParam {String} id Identificação do Responsável da tarefa(opcional).
     * @apiExample Exemplo de Uso:
     * curl -i http://kenkoapi/RunrunIt/tasks/jose-maria
     *
     * @apiSuccessExample {json} Resposta de sucesso:
     *     HTTP/1.1 200 OK
     *     {
     * "id": 7503,
     * "uid": 7503,
     * "_permission_": true,
     * "title": "Criação da Página Principal da API",
     * "is_working_on": true,
     * "responsible_id": "jefferson-brasilino",
     * "user_id": "hudson-moreno-escorcio-nepomuceno",
     * "type_id": 391563,
     * "project_id": 1722608,
     * "desired_date": null,
     * "desired_date_with_time": null,
     * "estimated_start_date": null,
     * "estimated_delivery_date": "2018-04-13T15:04:55-03:00",
     * "close_date": "2018-04-13T15:04:55-03:00",
     * "priority": 1,
     * "task_state_id": null,
     * "task_status_id": null,
     * "was_reopened": false,
     * "is_closed": false,
     * "on_going": false,
     * "team_id": 55283,
     * "tag_list": "",
     * "estimated_delivery_date_updated": true,
     * "last_estimated_at": "2018-04-13T08:18:49-03:00",
     * "queue_position": 1,
     * "scheduled_start_time": null,
     * "created_at": "2018-04-10T09:10:49-03:00",
     * "start_date": "2018-04-10T10:05:13-03:00",
     * "current_estimate_seconds": 90000,
     * "current_worked_time": 78357,
     * "current_evaluator_id": null,
     * "approved": null,
     * "evaluation_status": null,
     * "attachments_count": 0,
     * "task_tags": [],
     * "is_scheduled": false,
     * "client_name": "01 - UNIMED TERESINA",
     * "project_name": "CRIAÇÃO DA API UNIMED TERESINA",
     * "project_group_name": "01 - TECNOLOGIA DA INFORMAÇÃO",
     * "project_group_is_default": false,
     * "project_sub_group_name": "API",
     * "project_sub_group_is_default": false,
     * "type_name": "00.06.00 - TELA DE MOVIMENTAÇÃO - BAIXA COMPLEXIDADE",
     * "user_name": "Hudson Moreno Escorcio Nepomuceno",
     * "responsible_name": "Jefferson Brasilino",
     * "team_name": "Analistas de Sistemas - Operadoras",
     * "task_state_name": null,
     * "task_status_name": null,
     * "type_color": "11a6d4",
     * "state": "working_on",
     * "overdue": "on_schedule",
     * "time_worked": 78100,
     * "time_pending": 11900,
     * "time_total": 90000,
     * "time_progress": 86.7777777777778,
     * "activities_7_days_ago": 0,
     * "activities_6_days_ago": 0,
     * "activities_5_days_ago": 0,
     * "activities_4_days_ago": 0,
     * "activities_3_days_ago": 24886,
     * "activities_2_days_ago": 22830,
     * "activities_1_days_ago": 20389,
     * "activities": 68105,
     * "follower_ids": [],
     * "workflow_id": null,
     * "repetition_rule_id": null,
     * "checklist_id": null
     *     }
     */
    public function getTasks($req, $res, $ser, $app)
    {
        $rs = $this->model->getTasks($req->userId);
        $app->kenkoResponse->response($rs);
    }

    /**
     * @api {get} /RunrunIt/users/:id Busca os Usuários.
     * @apiVersion 0.2.0
     * @apiName getUsersRunrunIt
     * @apiGroup RunrunIt
     * @apiParam {String} id Identificação do Usuário(opcional).
     * @apiExample Exemplo de Uso:
     * curl -i http://kenkoapi/RunrunIt/users/jose-maria
     *
     * @apiSuccessExample {json} Resposta de sucesso:
     *     HTTP/1.1 200 OK
     *     {
     * "id": "francisco-leal",
     * "name": "Josué Leal",
     * "email": "francisco.leal@unimedteresina.com.br",
     * "avatar_url": "https://d22iebrrkdwkpr.cloudfront.net/avatars/unimed-teresina/francisco-leal/5cda6d0710368f23a2e5e82d32d0bcd7mini.jpg",
     * "avatar_large_url": "https://d22iebrrkdwkpr.cloudfront.net/avatars/unimed-teresina/francisco-leal/5cda6d0710368f23a2e5e82d32d0bcd7regular.jpg",
     * "compact_side_menu": null,
     * "cost_hour": 11.36,
     * "is_master": false,
     * "is_manager": false,
     * "is_auditor": false,
     * "can_create_client_project_and_task_types": false,
     * "time_zone": "America/Fortaleza",
     * "position": "",
     * "on_vacation": false,
     * "birthday": "1981-02-23",
     * "phone": "+558699133348",
     * "gender": "male",
     * "marital_status": "married",
     * "in_company_since": null,
     * "is_certified": false,
     * "language": "pt-BR",
     * "alt_id": "8662d9bde6a94013accdacb5a2f1dcc8",
     * "oid": "1bad9dfbfe8c83",
     * "see_archived_clients_and_projects": null,
     * "budget_manager": false,
     * "shifts": [
     * {
     * "weekday": 0,
     * "work_day": false,
     * "shift_start": "08:00:00",
     * "lunch_start": "12:00:00",
     * "lunch_end": "13:00:00",
     * "shift_end": "18:00:00"
     * },
     * {
     * "weekday": 1,
     * "work_day": true,
     * "shift_start": "08:00:00",
     * "lunch_start": "12:00:00",
     * "lunch_end": "13:00:00",
     * "shift_end": "18:00:00"
     * },
     * {
     * "weekday": 2,
     * "work_day": true,
     * "shift_start": "08:00:00",
     * "lunch_start": "12:00:00",
     * "lunch_end": "13:00:00",
     * "shift_end": "18:00:00"
     * },
     * {
     * "weekday": 3,
     * "work_day": true,
     * "shift_start": "08:00:00",
     * "lunch_start": "12:00:00",
     * "lunch_end": "13:00:00",
     * "shift_end": "18:00:00"
     * },
     * {
     * "weekday": 4,
     * "work_day": true,
     * "shift_start": "08:00:00",
     * "lunch_start": "12:00:00",
     * "lunch_end": "13:00:00",
     * "shift_end": "18:00:00"
     * },
     * {
     * "weekday": 5,
     * "work_day": true,
     * "shift_start": "08:00:00",
     * "lunch_start": "12:00:00",
     * "lunch_end": "13:00:00",
     * "shift_end": "17:00:00"
     * },
     * {
     * "weekday": 6,
     * "work_day": false,
     * "shift_start": "08:00:00",
     * "lunch_start": "12:00:00",
     * "lunch_end": "13:00:00",
     * "shift_end": "18:00:00"
     * }
     * ],
     * "is_mensurable": true,
     * "time_tracking_mode": "smart",
     * "demanders_count": 6,
     * "partners_count": 1
     *      }
     *
     */
    public function getUsers($req, $res, $ser, $app)
    {
        $rs = $this->model->getUsers($req->userId);
        $app->kenkoResponse->response($rs);
    }


}