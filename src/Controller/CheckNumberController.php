<?php

namespace App\Controller;

use App\Form\CheckNumberType;
use App\Service\FamilyTreeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckNumberController extends AbstractController
{
    private $service;

    public function __construct()
    {
        $this->service = new FamilyTreeService();
    }

    /**
     * @Route("/check-number", name="app_check_number")
     */
    public function index(Request $request): Response
    {
        $url = 'https://www.familysearch.org/service/search/cat/v2/search?count=20&query=+film_number:';
        $form = $this->createForm(CheckNumberType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
//            var_dump($data);
            $items = $this->service->getItems($url . $data['film_number']);
            return $this->renderForm('show_films.html.twig', [
                'film_number' => $data['film_number'], //$filmNumber,
                'titles' => $items,
            ]);
        }

        return $this->renderForm('get_numbers.html.twig', [
            'form' => $form,
        ]);
    }
}
