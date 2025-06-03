<?php

namespace App\Controller\Pages;

use App\Utils\View;
use App\Model\Entity\Organization;

class About extends Pages
{
    /* 
    Método responsavel por retorna o conteudo (view) da nossa home
    @return string
    */
    public static function getAbout()
    {
        //ORGANIZAÇÃO
        $obOrganization = new Organization;

        //VIEW DA HOME
        $content = View::render('pages/about', [
            'name' => $obOrganization->name,
            'description' => $obOrganization->description,
            'site' => $obOrganization->site
        ]);
        //RETORNA A VIEW DA PÁGINA
        return parent::getPage('SOBRE > ZORITTODEV', $content);
    }
}
