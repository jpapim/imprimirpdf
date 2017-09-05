<?php
require_once('Database.php');
require_once('PBancoDeDados.php');
require_once('NNegocio.php');



//print_r($_GET['id_prova']);

$dadosProva = new PBancoDeDados();
$arQuestoesProva = $dadosProva->buscarQuestoesProva($_GET['id_prova']);
//echo "<pre>";
//print_r($arQuestoesProva);
//echo "</pre>";
//
//die('Peguei o ID');

    $nrQuestoes = count($arQuestoesProva);
    if ($nrQuestoes == 0) { ?>
        <div style="text-align: left" class="row form-group">
            <div class="col-md-12">
                <?php
                echo "<h2> Nao existem questoes adicionadas a esta avaliação</h2>";
                ?>
            </div>
        </div>
        <?php
    } else {
        ?>
        <div>
            <?php
            $i = 1;
            foreach ($arQuestoesProva as $key => $item) {
                $arQuestoesProva = $dadosProva->buscarQuestoesProva($_GET['id_prova']);
                echo "<h4 class='questao'> QUESTÃO". " ".$i++. "</h4>";

                echo "<p>" . $arQuestoesProva['tx_enunciado'] . "</p>";



//                $arAlternativaQuestao = $dadosProva->buscarAlternativasQuestao(['id_questao' => $item['id_questao']]);
//                $j = 'a';
//                foreach ($arAlternativaQuestao as $key => $alternativa) {
//                    echo "<span>" . $j++ . ") " . str_replace("/assets/kcfinder/upload/images/","../../../../../public/assets/kcfinder/upload/images/",$alternativa['tx_alternativa_questao'] ). "</span><br/>";
//                }
//                echo "<br/>";
            } ?>
        </div>

        <?php
    }
    ?>