<?php
require_once('Database.php');

function recuperarNomeDosTecnicos($nome_tecnico) {

    $sql = "SELECT
                id, first_name || ' ' || last_name as nome_tecnico
            FROM
                users
            WHERE
                valid_id = 1
                AND first_name ilike '%$nome_tecnico%'
            ORDER BY
                first_name
	";

    $stmt = Database::prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

$termo = $_GET['term'];
$arTecnicos = recuperarNomeDosTecnicos($termo);
foreach($arTecnicos as $nomes){
    $arTecnicosFiltradas[] = $nomes->nome_tecnico;
}
print_r(json_encode($arTecnicosFiltradas));
die();