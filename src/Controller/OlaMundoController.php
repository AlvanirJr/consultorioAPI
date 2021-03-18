<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OlaMundoController
{
    /**
     * @Route ("/ola")
     */
    public function olamundo(Request $request) : Response
    {
        $pathInfo = $request->getpathInfo(); 
        $parametro = $request->query->all();
        return new JsonResponse(['mensagem' => 'ola mundo ',
                                'pathInfo' => $pathInfo,
                                'parametros'  => $parametro]);
    }
}