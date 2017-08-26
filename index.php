<?php
require_once('Database.php');
require_once('PBancoDeDados.php');
require_once('NNegocio.php');
?>
<html>
<head>
    <link rel="stylesheet" href="assets/css/jquery-ui.css" />
    <!-- Bootstrap CSS -->
<!--    <link rel="stylesheet" href="assets/css/bootstrap.min.css">-->

    <script type="text/javascript" src="assets/js/infra.js"></script>
    <script type="text/javascript" src="assets/js/jquery.js"></script>
    <script type="text/javascript" src="assets/js/jquery-ui.js"></script>
    <style>
        /*body {*/
            /*padding-top: 20px;*/
            /*padding-left: 10px;*/
            /* Required padding for .navbar-fixed-top. Remove if using .navbar-static-top. Change if height of navigation changes. */
        /*}*/

        a.export, a.export:visited {
            text-decoration: none;
            color:#000;
            background-color:#ddd;
            border: 1px solid #ccc;
            padding:8px;
        }
        .titulo{
            background-color:#ddd;
            color:#000;
            font-weight: bold;
            text-align: center;
        }
    </style>
    <script type="text/javascript" src="assets/js/export/jquery.btechco.excelexport.js"></script>
    <script type="text/javascript" src="assets/js/export/jquery.base64.js"></script>

    <!-- Include all compiled plugins (below), or include individual files as needed -->
<!--    <script src="assets/js/bootstrap.min.js"></script>-->
</head>
<body>
<form action="" method="POST">

    <input type="submit" name="enviar" value="enviar">
    <input type="button" name="limpar" id="limpar" value="limpar">
    <a href="#" class="export" id="btnExportCSV">Exportar Dados para CSV</a>
    <a href="#" class="export" id="btnExportXLS">Exportar Dados para XLS</a>

</form>
<a href="index.php">Voltar</a>
<hr/>
<h2>Relatório de acompanhamento das atividades dentro do horário contratado.</h2>
<div id="dvData">
    <table bordercolor="" width="100%" id="tblExport" style="border:1px solid black; ">
        <!--<thead>-->
        <tr class="titulo">
            <td>#</td>
            <td>Nome</td>
            <td>Data de Aplicação</td>
            <td>Data de Geraçãot</td>
            <td>Descricao</td>
            <td>Gerar</td>
        </tr>
        <!--        </thead>-->
        <!--        <tbody>-->
        <?php
        if (isset($_POST['enviar']) && $_POST['enviar']) {

            $arHorasAtividades = array();
            $relatorio_acompanhamento = new PBancoDeDados();
            $rs = $relatorio_acompanhamento->listarTodasProvas();
            if($rs){
                $cont = 0;
                foreach ($rs as $resultado) {
                    echo $cont % 2 == 0 ? "<tr bgcolor='#FFFFFF'>":"<tr bgcolor='#a9a9a9'>";
                    $cont ++;

                    $dt_aplicacao_prova = Infra::converterDataHoraBanco2Brazil($resultado->dt_aplicacao_prova);
                    $dt_geracao_prova = Infra::converterDataHoraBanco2Brazil($resultado->dt_geracao_prova);

                    echo NNegocio::exibirTextoNaColuna($resultado->id_prova);
                    echo NNegocio::exibirTextoNaColuna($resultado->nm_prova);
                    echo NNegocio::exibirTextoNaColuna($dt_aplicacao_prova);
                    echo NNegocio::exibirTextoNaColuna($dt_geracao_prova );
                    echo NNegocio::exibirTextoNaColuna($resultado->ds_prova);
                    echo "<td><a href='gerarPdf.php?id_prova=" . $resultado->id_prova ."'> Imprimir</a></td>";
                    echo "</tr>";
                }
            }else{
                echo "<tr>";
                echo "<td align='center' colspan='6'>Não Existem dados cadastrados</td>";
                echo "</tr>";
            }

        } else {
            echo "<tr>";
            echo "<td align='center' colspan='9'>Nenhuma informação para exibição</td>";
            echo "</tr>";
        }
        ?>
        <!--        </tbody>-->
    </table>
</div>
<script type="text/javascript" language="javascript" class="init">
    $(document).ready(function () {

        $("#btnExportCSV").click(function (e) {
            exportTableToCSV.apply(this, [$('#dvData>table'), 'acompanhamento_das_atividades_' + getDataAtualParaNomearArquivo() + '.csv', 'csv']);
            // IF CSV, don't do event.preventDefault() or return false
            // We actually need this to be a typical hyperlink
        });

        $("#btnExportXLS").click(function () {
            $("#tblExport").btechco_excelexport({
                containerid: "tblExport"
                , datatype: $datatype.Table
                , filename: 'acompanhamento_das_atividades_' + getDataAtualParaNomearArquivo()
            });
        });

    });



</script>

<br/>
<a href="index.php">Voltar</a>
</body>
</html>

		
		
