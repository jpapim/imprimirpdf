<?php
require_once('Infra.php');

class PBancoDeDados {

    /**
     * Recupera todos os tckets que possuem algum tipo de inconsistência que pode afetar o faturamento das horas.
     * @param null $dt_inicio
     * @param null $dt_fim
     * @return array
     */
    function listarTodasProvas() {

        $sql = "
                SELECT 
                      prova.id_prova AS id_prova, 
                      prova.nm_prova AS nm_prova, 
                      prova.dt_aplicacao_prova AS dt_aplicacao_prova, 
                      prova.dt_geracao_prova AS dt_geracao_prova, 
                      prova.ds_prova AS ds_prova 
                FROM 
                      prova 
                WHERE 
                      prova.cs_ativo = 1 
                ORDER BY 
                      nm_prova DESC
        ";

        #die("<pre>".$sql."</pre>");
        $stmt = Database::prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function buscarProva($id_prova) {

        $sql = "
                SELECT 
                      prova.id_prova AS id_prova, 
                      prova.nm_prova AS nm_prova, 
                      prova.dt_aplicacao_prova AS dt_aplicacao_prova, 
                      prova.dt_geracao_prova AS dt_geracao_prova, 
                      prova.ds_prova AS ds_prova 
                FROM 
                      prova 
                WHERE 
                      prova.cs_ativo = 1 
                      AND prova.id_prova = " . $id_prova . "
                ORDER BY 
                      nm_prova DESC
        ";

        #die("<pre>".$sql."</pre>");

        $stmt = Database::prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function buscarQuestoesProva($id_prova) {

        $sql = "

SELECT qp.id_questao_prova, qp.id_questao, qp.id_prova, q.tx_enunciado
FROM questoes_prova as qp
JOIN questao AS q ON qp.id_questao = q.id_questao
WHERE                     
qp.id_prova = " . $id_prova . "
ORDER BY 
qp.id_questao ASC
                
        ";

        //                SELECT
//                      questoes_prova.id_questao_prova AS id_questao_prova,
//                      questoes_prova.id_questao AS id_questao,
//                      questoes_prova.id_prova AS id_prova
//                FROM
//                      questoes_prova
//                WHERE
//                      questoes_prova.id_prova = " . $id_prova . "
//                ORDER BY
//                      questoes_prova.id_questao ASC
//        ";

        #die("<pre>".$sql."</pre>");

        $stmt = Database::prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }




    function buscarQuestao($id_questao) {

        $sql = "
                SELECT t.id,
                        (u2.first_name::text || ' '::text) || u2.last_name::text AS nome_tecnico,
                        ( SELECT ticket.tn
                               FROM ticket
                              WHERE ticket.id = lr.source_key::integer) AS ticket_pai,
                        t.tn AS ticket,
                        (fcs.cd_atividade::text || ' - '::text) || fcs.nome_atividade::text AS atividade,
                        ts.name AS estado,
                        q.name AS torre,
                        di.value_date::date AS data,
                        di.value_date AS data_hora_inicio,
                        df.value_date AS data_hora_fim,
                        df.value_date - di.value_date AS tempo_execucao,
                        (u1.first_name::text || ' '::text) || u1.last_name::text AS criado_por,
                        t.create_time AS \"data_criação\"
                FROM ticket t
                         LEFT JOIN users u1 ON t.create_by = u1.id
                         LEFT JOIN users u2 ON t.user_id = u2.id
                         LEFT JOIN link_relation lr ON t.id::character varying::text = lr.target_key::text
                         LEFT JOIN ticket_state ts ON t.ticket_state_id = ts.id
                         LEFT JOIN queue q ON t.queue_id = q.id
                         LEFT JOIN dynamic_field_value di ON t.id = di.object_id AND di.field_id = 26
                         LEFT JOIN dynamic_field_value df ON t.id = df.object_id AND df.field_id = 27
                         LEFT JOIN faturamento.atividade_catalogo fcs ON t.service_id = fcs.id_atividade_catalogo
                WHERE
                        q.name != 'Spam'
                        AND (
                                ( SELECT ticket.tn FROM ticket	WHERE ticket.id = lr.source_key::integer) is null
                                OR ts.name != 'Encerrado pelo analista' OR di.value_date is null OR df.value_date is null OR (df.value_date - di.value_date > '23:59:59')
                            )
        ";

        #die("<pre>".$sql."</pre>");
        $stmt = Database::prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function buscarAlternativasQuestao($id_questao) {

        $sql = "
                SELECT t.id,
                        (u2.first_name::text || ' '::text) || u2.last_name::text AS nome_tecnico,
                        ( SELECT ticket.tn
                               FROM ticket
                              WHERE ticket.id = lr.source_key::integer) AS ticket_pai,
                        t.tn AS ticket,
                        (fcs.cd_atividade::text || ' - '::text) || fcs.nome_atividade::text AS atividade,
                        ts.name AS estado,
                        q.name AS torre,
                        di.value_date::date AS data,
                        di.value_date AS data_hora_inicio,
                        df.value_date AS data_hora_fim,
                        df.value_date - di.value_date AS tempo_execucao,
                        (u1.first_name::text || ' '::text) || u1.last_name::text AS criado_por,
                        t.create_time AS \"data_criação\"
                FROM ticket t
                         LEFT JOIN users u1 ON t.create_by = u1.id
                         LEFT JOIN users u2 ON t.user_id = u2.id
                         LEFT JOIN link_relation lr ON t.id::character varying::text = lr.target_key::text
                         LEFT JOIN ticket_state ts ON t.ticket_state_id = ts.id
                         LEFT JOIN queue q ON t.queue_id = q.id
                         LEFT JOIN dynamic_field_value di ON t.id = di.object_id AND di.field_id = 26
                         LEFT JOIN dynamic_field_value df ON t.id = df.object_id AND df.field_id = 27
                         LEFT JOIN faturamento.atividade_catalogo fcs ON t.service_id = fcs.id_atividade_catalogo
                WHERE
                        q.name != 'Spam'
                        AND (
                                ( SELECT ticket.tn FROM ticket	WHERE ticket.id = lr.source_key::integer) is null
                                OR ts.name != 'Encerrado pelo analista' OR di.value_date is null OR df.value_date is null OR (df.value_date - di.value_date > '23:59:59')
                            )
        ";

        #die("<pre>".$sql."</pre>");

        $stmt = Database::prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

}