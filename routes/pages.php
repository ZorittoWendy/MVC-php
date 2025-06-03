<?php

use App\Http\Response;
use App\Controller\Pages;

//ROTA HOME
$obRouter->get('/', [
    function () {
        return new Response(200, Pages\Home::getHome());
    }
]);

//ROTA DE SOBRE
$obRouter->get('/sobre', [
    function () {
        return new Response(200, Pages\About::getAbout());
    }
]);

//ROTA DINÁMICA
$obRouter->get('/pagina/{idPagina}/{acao}', [
    function ($idPagina, $acao) {
        return new Response(200, 'Pagina 10' . $idPagina . ' _ ' . $acao);
    }
]);
