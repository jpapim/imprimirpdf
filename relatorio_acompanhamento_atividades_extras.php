<?php
require_once('Database.php');
require_once('PBancoDeDados.php');
require_once('NNegocio.php');
?>
<html>
<head>
    <link rel="stylesheet" href="assets/css/jquery-ui.css" />
    <script type="text/javascript" src="assets/js/infra.js"></script>
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/jquery-ui.js"></script>
    <style>
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

    <!-- Datatables -->
    <link rel="stylesheet" href="assets/css/datatables/jquery.dataTables.css" />
    <script type="text/javascript" src="assets/js/datatables/jquery.dataTables.min.js"></script>
</head>
<body>
<form action="" method="POST">
    <h2>Preencha os Filtros Abaixo:</h2>
    <label for="dt_inicio"> Data Início</label>
    <input type="text" id="dt_inicio" name="dt_inicio" /><br/><br/>
    <label for="dt_fim"> Data Fim</label>
    <input type="text" id="dt_fim" name="dt_fim" /><br/><br/>
    <label for="nome_do_tecnico"> Nome do Técnico</label>
    <input type="text" id="nome_do_tecnico" name="nome_do_tecnico" />

    <input type="submit" name="enviar" value="enviar">
    <input type="button" name="limpar" id="limpar" value="limpar">
    <a href="#" class="export" id="btnExportCSV">Exportar Dados para CSV</a>
    <a href="#" class="export" id="btnExportXLS">Exportar Dados para XLS</a>
</form>
<a href="index.php">Voltar</a>
<hr/>
<h2>Relatório de acompanhamento das horas extras</h2>
<div id="dvData">
    <table bordercolor="" width="100%" id="tblExport" style="border:1px solid black; ">
        <!--        <thead>-->
        <tr class="titulo">
            <td>Técnico</td>
            <td>Ticket Pai</td>
            <td>Ticket</td>
            <td>Torre</td>
            <td>Estado</td>
            <td>Atividade</td>
            <td>Data Inicio</td>
            <td>Data Término</td>
            <td>Tempo Execução</td>
        </tr>
        <!--        </thead>-->
        <!--        <tbody>-->
                <?php
                if (isset($_POST['enviar']) && $_POST['enviar']) {
                    /*Grava os Dados do Formulário na Sessão*/
                    $_SESSION['nome_do_tecnico'] = isset($_POST['nome_do_tecnico']) ? $_POST['nome_do_tecnico'] : "";
                    $_SESSION['dt_inicio'] = isset($_POST['dt_inicio']) ? $_POST['dt_inicio'] : "";
                    $_SESSION['dt_fim'] = isset($_POST['dt_fim']) ? $_POST['dt_fim'] : "";

                    $arHorasAtividades = array();
                    $relatorio_acompanhamento = new PBancoDeDados();
                    $rs = $relatorio_acompanhamento->recuperarDadosAcompanhamentoDasHorasExtras($_POST['dt_inicio'], $_POST['dt_fim'], $_POST['nome_do_tecnico']);
                    if($rs){
                        $cont = 0;
                        foreach ($rs as $resultado) {
                            echo $cont % 2 == 0 ? "<tr bgcolor='#FFFFFF'>":"<tr bgcolor='#a9a9a9'>";
                            $cont ++;

                            $data_hora_inicio = Infra::converterDataHoraBanco2Brazil($resultado->data_hora_inicio);
                            $data_hora_fim = Infra::converterDataHoraBanco2Brazil($resultado->data_hora_fim);
                            $verificar_duracao_atividade = Infra::calcularDiferencaEntreDatasBrazilEmDias($data_hora_inicio, $data_hora_fim);

                            $arHorasAtividades[] = $resultado->tempo_execucao;

                            echo NNegocio::validarColunaNomeDoTecnico($resultado->nome_tecnico);
                            echo NNegocio::validarColunaTicketPai($resultado->ticket_pai);
                            echo NNegocio::validarColunaTicketComLink($resultado->ticket, $resultado->id);
                            echo NNegocio::validarColunaTorreDoAnalista($resultado->torre);
                            echo NNegocio::validarColunaEstadoDaDemanda($resultado->estado);
                            echo NNegocio::validarColunaAtividadeRealizada($resultado->atividade);
                            echo NNegocio::validarColunaDataHoraInicioDaAtividade($data_hora_inicio);
                            echo NNegocio::validarColunaDataHoraFimDaAtividade($data_hora_fim);
                            echo NNegocio::validarColunaTempoDeExecucaoDaAtividade($resultado->tempo_execucao, $verificar_duracao_atividade);
                            #echo $resultado->tempo_execucao && $verificar_duracao_atividade < 1 ? "<td align='left'>$resultado->tempo_execucao</td>": "<td align='center' bgcolor='red'>Vazio!</td>";
                            echo "</tr>";
                        }
                        echo "<tr style='font-weight: bold;'>";
                        echo "<td align='left' colspan='8'>Total</td>";
                        echo "<td align='left'>". Infra::somarHoras($arHorasAtividades)."</td>";
                        echo "</tr>";

                    }else{
                        echo "<tr>";
                        echo "<td align='center' colspan='9'>Não Existem dados cadastrados para este técnico - " . $_POST['nome_do_tecnico'] . "</td>";
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
    $(document).ready(function() {
        $("#nome_do_tecnico").autocomplete({
            minLength: 2,
            source: "ajax_autocomplete_nome_tecnico.php"
        });

        //$('#tblExport').DataTable();

        $('#limpar').click(function(){
            $('#nome_do_tecnico').val('');
            $('#dt_inicio').val('');
            $('#dt_fim').val('');
        });


        $("#dt_inicio").datepicker({
            //format: 'dd/mm/yyyy',
            language: 'pt-BR',
            separator: ' ',
            //minDate: new Date(),
            dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
            dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
            dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
            monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            nextText: 'Próximo',
            prevText: 'Anterior',
            dateFormat: 'dd/mm/yy'
        });

        $("#dt_fim").datepicker({
            //format: 'dd/mm/yyyy',
            language: 'pt-BR',
            separator: ' ',
            //minDate: new Date(),
            dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
            dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
            dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
            monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            nextText: 'Próximo',
            prevText: 'Anterior',
            dateFormat: 'dd/mm/yy'
        });

        /*Persiste os dados da Busca no Formulário apos o Submit.*/
        <?php if (isset($_SESSION['nome_do_tecnico'])) {?>
        $('#nome_do_tecnico').val('<?php echo $_SESSION['nome_do_tecnico']; ?>');
        <?php }?>
        <?php if (isset($_SESSION['dt_inicio'])) {?>
        $('#dt_inicio').val('<?php echo $_SESSION['dt_inicio']; ?>');
        <?php }?>
        <?php if (isset($_SESSION['dt_fim'])) {?>
        $('#dt_fim').val('<?php echo $_SESSION['dt_fim']; ?>');
        <?php }?>

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

<a href="index.php">Voltar</a>
</body>
</html>

		
		
