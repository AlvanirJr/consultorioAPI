<?php

namespace App\Helper;

use App\Entity\Medico;

class MedicoFactory 
{
    public function criarMedico(string $json) : Medico
    {
        $dadoJson = json_decode($json);

        $medico = new Medico();
        $medico->crm = $dadoJson->crm;
        $medico->nome = $dadoJson->nome;

        return $medico;
    }
}