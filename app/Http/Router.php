<?php

namespace App\Http;

use \Closure;
use \Exception;
use Reflection;
use \ReflectionFunction;

class Router
{
    /* 
    URL completa do projeto (raiz) 
    @var string 
    */
    private $url = '';

    /* 
    prefixo de todas as rotas 
    @var string
    */
    private $prefix = '';

    /* 
    Indice de rotas
    @var string
    */
    private $routes = [];

    /* 
    Instancia de Request
    @var Request
    */
    private $request;

    /* 
    Metódo responsável por iniciar a classe
    @param string url
    */
    public function __construct($url)
    {
        $this->request = new Request();
        $this->url = $url;
        $this->setPrefix();
    }

    /* 
    Método responsável por defini os prefixos das rotas
    */
    private function setPrefix()
    {
        //INFORMAÇÕES DA URL ATUAL
        $parseUrl = parse_url($this->url);

        //DEFINE O PREFIXO
        $this->prefix = $parseUrl['path'] ?? '';
    }

    /* 
        Método responsável por adicionar uma rota na classe
        @param string Método
        @param string $route
        @param array params
    */
    private function addRoute($method, $route, $params = [])
    {
        //VALIDAÇÃO DOS PARÂMETROS
        foreach ($params as $key => $value) {
            if ($value instanceof Closure) {
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }

        //VARIÁVEIS DE ROTA
        $params['variables'] = [];

        //PADRÃO DE VALIDAÇÃO DAS VARIÁBVEIS DAS ROTAS
        $patternVarible = '/{(.*?)}/';
        if (preg_match_all($patternVarible, $route, $matches)) {
            $route = preg_replace($patternVarible, '(.*?)', $route);
            $params['variables'] = $matches[1];
        }

        //PADRÃO DE VALIDAÇÃO DA URL
        $patternRoute = '/^' . str_replace('/', '\/', $route) . '$/';

        //ADICIONA A ROTA DENTRO DA CLASSE
        $this->routes[$patternRoute][$method] = $params;
    }

    /* 
    Método responsável por uma rota de GET 
    @param string $route 
    @param string $params
    */
    public function get($route, $params = [])
    {
        return $this->addRoute('GET', $route, $params);
    }

    /* 
    Método responsável por definir uma rota de POST
    @param string $route
    @param array $params
    */
    public function post($route, $params)
    {
        return $this->addRoute('POST', $route, $params);
    }

    /* 
    Método responsável por definir uma rota de PUT
    @param string $route
    @param array $params
    */
    public function put($route, $params = [])
    {
        return $this->addRoute('PUT', $route, $params);
    }

    /* 
    Método responsável por definir uma rota de DELETE
    @param string $route
    @param array $params
    */
    public function delete($route, $params = [])
    {
        return $this->addRoute('DELETE', $route, $params);
    }

    /* Método responsável por retornar a URI desconsiderando o prefixo
    @return string
    */
    private function getUri()
    {
        //URP DA REQUEST
        $uri = $this->request->getUri();

        //FATIA A URI
        $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];

        //RETORNA A URI SEM PREFIX
        return end($xUri);
    }

    /* Método reposável por retorna os dados da rota atual
    @return array
    */
    private function getRoute()
    {
        //URI
        $uri = $this->getUri();

        //METHOD    
        $httpMethod = $this->request->getHttpMethod();

        //VALIDA AS ROTAS
        foreach ($this->routes as $patternRoute => $methods) {
            //VERIFICA SE A URI BATE O PADRÃO
            if (preg_match($patternRoute, $uri, $matches)) {
                //VERIFICA O MÉTODO
                if (isset($methods[$httpMethod])) {
                    //REMOVE A PRIMEIRA POSIÇÃO
                    unset($matches[0]);

                    //VARIAVEIS PROCESSADAS
                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
                    $methods[$httpMethod]['variables']['request'] = $this->request;

                    //RETORNO DOS PARÂMETROS DA ROTA
                    return $methods[$httpMethod];
                }
                //MÉTODO NÃO PERMITIDO/DEFINIDO
                throw new Exception("Método não permitido", 405);
            }
        }
        //URL NÃO ENCONTRADA
        throw new Exception("URl não encontrada", 404);
    }

    /* 
    Método responsável por executar a rota atual
    return Response
    */
    public function run()
    {
        try {
            //OBTÉM A ROTA ATUAL
            $route = $this->getRoute();

            //VERIFICA O CONTROLADOR
            if (!isset($route['controller'])) {
                throw new Exception('A URL não pode ser processada', 500);
            }
            //ARGUMENTOS DA FUNÇÃO
            $args = [];

            //REFLECTION
            $reflection = new ReflectionFunction($route['controller']);
            foreach ($reflection->getParameters() as $parameter) {
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }

            //RETORNA A EXECUÇÃO DA FUNÇÃO
            return call_user_func_array($route['controller'], $args);
        } catch (Exception $e) {
            return new Response($e->getCode(), $e->getMessage());
        }
    }
}
