<?php
require_once('Infra.php');

class NNegocio
{
    /**
     * @param $torre
     * @return string
     */
    public static function exibirTextoNaColuna($texto)
    {
        $resposta = "<td align='left'>$texto</td>";

        return $resposta;
    }

    public static function exibirNumeroComoTextoNaColuna($texto)
    {
        $resposta = "<td align='left'>&nbsp;$texto</td>";

        return $resposta;
    }

    /**
     * @param $texto
     * @return string
     */
    public static function exibirTextoNaColunaSubtituiPontoPorVirgula($texto)
    {
        $texto_substituido = str_replace('.',',',$texto);
        $resposta = "<td align='left'>$texto_substituido</td>";

        return $resposta;
    }

    /**
     * @param $texto
     * @return string
     */
    public static function exibirTextoNaColunaSubtituiVirgulaPorPonto($texto)
    {
        $texto_substituido = str_replace(',','.',$texto);
        $resposta = "<td align='left'>$texto_substituido</td>";

        return $resposta;
    }

    public static function exibirTextoNaColunaFormatoMoeda($texto, $nrCasasDecimais = 2)
    {
        $valor = number_format($texto, $nrCasasDecimais,',', '.');
        $resposta = "<td align='left'>$valor</td>";

        return $resposta;
    }



}