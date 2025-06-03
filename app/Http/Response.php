<?php

namespace App\Http;

class Response
{
    /* Codigo do status do HTTP
    @var interger
    */
    private $httpCode = 200;

    /* Cabeçalho do response 
    @var array
    */
    private $headers = [];

    /* Tipo de conteudo que está sendo retornado
    @var string
    */
    private $contentType = 'text/html';

    /* Conteúdo do response 
    @var mixed
    */
    private $content;

    /* Metódo responsável por iniciar a classe e definir os valores
    @param interge $httpcode
    @param mixed $content
    @param string $contentType
    */
    public function __construct($httpCode, $content, $contentType = 'text/html')
    {
        $this->httpCode = $httpCode;
        $this->content = $content;
        $this->setContentType($contentType);
    }

    /* Metódo resposável por alterar o content type do response
    @param string $contentType
    */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        $this->addHeader('content-type', $contentType);
    }

    /* Metódo responsável por adicionar um registro no cabecalho da Response
    @param string $key
    @param string $value
    */
    public function addHeader($key, $value)
    {
        $this->headers[$key] = $value;
    }

    /* Metódo resposável por enviar os headers para o navegador */
    private function sendHeaders()
    {
        //STATUS
        http_response_code($this->httpCode);

        //ENVIAR HEARDERS
        foreach ($this->headers as $key => $value) {
            header($key . ': ' . $value);
        }
    }

    /* Metódo resposável por enviar resposta para o usuário */
    public function sendResponse()
    {
        //ENVIAR OS HEADERS
        $this->sendHeaders();

        //IMPRIME O CONTEUDO
        switch ($this->contentType) {
            case 'text/html':
                echo $this->content;
                exit;
        }
    }
}
