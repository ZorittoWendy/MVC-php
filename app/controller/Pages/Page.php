<?php

namespace App\controller\Pages;

use App\Utils\View;

class Page
{
    /* 
    Método responsável por renderizar o topo da página 
    @return string
    q*/
    private static function getHeader()
    {
        return View::render('pages/header');
    }
    /* 
    Método responsável por renderizar o rodapé da página
    @return string
    */
    private static function getFooter()
    {
        return View::render('pages/footer');
    }
    /* 
    Método responsavel por retorna o conteudo (view) da nossa Pagina generica
    @return string
    */
    public static function getPage($title, $content)
    {
        return View::render('pages/page', [
            'title' => $title,
            'header' => self::getHeader(),
            'content' => $content,
            'footer' => self::getFooter(),
        ]);
    }
}
