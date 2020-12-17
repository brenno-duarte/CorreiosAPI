<?php

namespace CorreiosAPI;

use PHPHtmlParser\Dom;

class CorreiosAPI
{
    /**
     * curl
     *
     * @var mixed
     */
    private $curl;

    /**
     * buscaCEP
     *
     * @param string $cep
     * @param string $estado
     * @param string $cidade
     * @param string $logradouro
     * @return object
     */
    public function buscaCEP(
        ?string $cep,
        string $estado = null,
        string $cidade = null,
        string $logradouro = null
    ): object {

        if ($cep == null) {
            $this->curlInit("https://viacep.com.br/ws/$estado/$cidade/$logradouro/json/");
            $decode = json_decode($this->curl);

            return (object)$decode;
        } else {
            $this->curlInit("https://viacep.com.br/ws/" . $cep . "/json/");
            $decode = json_decode($this->curl);

            return $decode;
        }
    }


    /**
     * calcPrecoPrazo
     * 
     * @param string $servico
     * @param string $cepOrigem
     * @param string $cepDestino
     * @param string $peso
     * @param string $formato
     * @param string $comprimento
     * @param string $altura
     * @param string $largura
     * @param string $diametro
     * @param string $maoPropria
     * @param string $valordeclarado
     * @param string $avisoRecebimento
     * @param string $retorno
     * @return object
     */
    public function calcPrecoPrazo(
        string $servico,
        string $cepOrigem,
        string $cepDestino,
        string $peso,
        string $formato,
        string $comprimento,
        string $altura,
        string $largura,
        string $diametro,
        string $maoPropria = "N",
        string $valordeclarado = "0",
        string $avisoRecebimento = "N",
        string $retorno = "xml"
    ): object {

        $url = sprintf(
            "ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?nCdServico=%s&sCepOrigem=%s&sCepDestino=%s&nVlPeso=%s&nCdFormato=%s&nVlComprimento=%s&nVlAltura=%s&nVlLargura=%s&nVlDiametro=%s&sCdMaoPropria=%s&nVlValorDeclarado=%s&sCdAvisoRecebimento=%s&StrRetorno=%s",
            $servico,
            $cepOrigem,
            $cepDestino,
            $peso,
            $formato,
            $comprimento,
            $altura,
            $largura,
            $diametro,
            $maoPropria,
            $valordeclarado,
            $avisoRecebimento,
            $retorno
        );

        $this->curlInit($url);

        $xml = simplexml_load_string($this->curl);

        return (object)$xml;
    }

    /**
     * rastrear
     *
     * @param string $code
     * @return object
     */
    public function rastrear(string $code): object
    {
        $this->curlInit("https://www2.correios.com.br/sistemas/rastreamento/resultado_semcontent.cfm", [
            'Objetos' => $code
        ]);

        $dom = new Dom();
        $dom->loadStr($this->curl);
        $codeObj = $dom->find('.codSro');
        $statusObj = $dom->find('.listEvent');

        $finalStatus = [
            'code' => $codeObj->outerHtml,
            'status' => $statusObj->outerHtml
        ];

        return (object)$finalStatus;
    }
    
    /**
     * disqueColeta
     *
     * @param string $cep
     * @return string
     */
    public function disqueColeta(string $cep): string
    {
        $this->curlInit("http://www2.correios.com.br/disqueColeta/default.cfm?err=ok&cepOrigem=$cep&valor=");

        $dom = new Dom();
        $dom->loadStr($this->curl);
        $status = $dom->find('font b');

        return $status->outerHtml;
    }
    
    /**
     * restricaoEntrega
     *
     * @param string $servico
     * @param string $cepOrigem
     * @param string $cepDestino
     * @return string
     */
    public function restricaoEntrega(string $servico, string $cepOrigem, string $cepDestino): string
    {
        $this->curlInit("http://www2.correios.com.br/sistemas/precosPrazos/restricaoentrega/resultado.cfm", [
            'servico' => $servico,
            'cepOrigem' => $cepOrigem,
            'cepDestino' => $cepDestino
        ]);

        $dom = new Dom();
        $dom->loadStr($this->curl);
        $status = $dom->find('.msg');

        return $status->outerHtml;
    }
    
    /**
     * consultaDetranCE
     *
     * @param string $placa
     * @return string
     */
    public function consultaDetranCE(string $placa): string
    {
        $this->curlInit("http://www2.correios.com.br/produtos_servicos/catalogo/regionais/detran_act.cfm", [
            'Ident' => $placa
        ]);

        $dom = new Dom();
        $dom->loadStr($this->curl);
        $status = $dom->find('.ctrlcontent');

        return $status->outerHtml;
    }

    /**
     * curl
     *
     * @param string $url
     * @param string $url
     * @return CorreiosAPI
     */
    private function curlInit(string $url, array $param = null): CorreiosAPI
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        if ($param != null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));
        }

        $this->curl = curl_exec($ch);
        curl_close($ch);

        return $this;
    }
}
