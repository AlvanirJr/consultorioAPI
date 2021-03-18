<?php

namespace App\Controller;

use App\Entity\Medico;
use App\Helper\MedicoFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedicoController extends AbstractController
{   
    /***
     * @var EntityManagerInterface
     */
    private $entityManager;
    private $medicoFactory;
    private $retorno;

    public function __construct(
        EntityManagerInterface $entityManager,
        MedicoFactory $medicoFactory
    )
    {
        $this->entityManager = $entityManager;
        $this->medicoFactory = $medicoFactory;
    }
    /**
     * @Route ("/medicos", methods={"POST"}) 
     */
    public  function novoMedico(Request $request) : Response
    {
        $requesicao = $request->getContent();
        $medico = $this->medicoFactory->criarMedico($requesicao);

        //Visualizar entidade
        $this->entityManager->persist($medico);
        $this->entityManager->flush();

        return new JsonResponse($medico);
    }

    /**
     * @Route ("/buscaMedicos", methods={"GET"})
     */

    public function buscaTodos() : Response
    {
        $repositorio = $this->getDoctrine()
                            ->getRepository(Medico::class);
        
        return new JsonResponse($repositorio->findAll());
    }

     /**
     * @Route ("/buscaMedicos/{id}", methods={"GET"})
     */
    public function buscarMedico(int $id) : Response
    {

        $result = $this->medicoBusca($id);

        $retorno = is_null($result) ? Response:: HTTP_NO_CONTENT : 200;

        return new JsonResponse($result, $retorno);
    }

     /**
     * @Route ("/atualizaMedico/{id}", methods={"PUT"})
     */
    public function atualizaMedico(int $id, Request $request) : Response
    {

        $requesicao = $request->getContent();
        $medicoEnviado = $this->medicoFactory->criarMedico($requesicao);
        $result = $this->medicoBusca($id);

        
        if(is_null($result))
        {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $result->crm = $medicoEnviado->crm;
        $result->nome = $medicoEnviado->nome;
        
        $this->entityManager->flush();

        return new JsonResponse($result);

    }

    /**
     * @Route ("/deleteMedico/{id}", methods={"DELETE"})
     */
    public function deleteMedico (int $id) : Response
    {
        $result = $this->medicoBusca($id);

        if(is_null($result))
        {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($result);
        $this->entityManager->flush();

        return new Response ('', Response::HTTP_NO_CONTENT);

    }

    public function medicoBusca(int $id){
        $repositorio = $this->getDoctrine()
                            ->getRepository(Medico::class);
        $result = $repositorio->find($id);
        

        return $result;
    }
    
}