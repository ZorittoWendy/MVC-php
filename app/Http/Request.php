<?php

namespace App\Http;

class Request
{

    /*  metodo HTTP da requisição
    @var string
    */
    private $httpMethod;

    /* URI da página 
    @var string
    */
    private $uri;

    /* Parâmetros da URL ($_GET) 
    @var array
    */
    private $queryParams = [];

    /* Variáveis recebidas no POST da página ($_POST)
    @var array
    */
    private $postVars = [];

    /* Cabeçalho da Requisição
    @var array */
    private $headers = [];

    public function __construct()
    {
        $this->queryParams = $_GET ?? [];
        $this->postVars = $_POST ?? [];
        $this->headers = getallheaders();
        $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->uri  = $_SERVER['REQUEST_URI'] ?? '';
    }

    /* Método responsavel por retorna o metodo Http da requisição 
        return string
    */

    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    /* Metodo Responsável por retorna a URI da requisição
    @return string
    */

    public function getUri()
    {
        return $this->uri;
    }

    /* Metódo responsável por retorna o da requisação
    @return array
    */

    public function getHeaders()
    {
        return $this->headers;
    }

    /* Metodo responsável por receber os parametros da URl da requisição
    @return arragy
    */
    public function getQueryParams()
    {
        return $this->queryParams;
    }

    /* Metódo responsável por retornar as variáveis POST da requisição
    @return array
    */
    public function getPostVars()
    {
        return $this->postVars;
    }
}
