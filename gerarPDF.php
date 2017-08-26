<?php
require_once('Database.php');
require_once('PBancoDeDados.php');
require_once('NNegocio.php');



print_r($_GET['id_prova']);

$banco_de_dados = new PBancoDeDados();
$arQuestoesProva = $banco_de_dados->buscarQuestoesProva($_GET['id_prova']);
echo "<pre>";
print_r($arQuestoesProva);
echo "</pre>";

die('Peguei o ID');