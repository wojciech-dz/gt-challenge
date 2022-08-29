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
            $items = $this->service->getItems($url . $data['film_number']);
            return $this->renderForm('show_films.html.twig', [
                'film_number' => $data['film_number'],
                'titles' => $items,
            ]);
        }

        return $this->renderForm('get_numbers.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/check-film", name="app_check_film", methods={"GET"})
     */
    public function checkFilmAction(Request $request): Response
    {
        $number = $_GET['number'];
        $url = 'https://www.familysearch.org/service/search/cat/v2/search?count=20&query=+film_number:';
        $items = $this->service->getItems($url . $number);
        return $this->renderForm('show_films.html.twig', [
            'film_number' => $number,
            'titles' => $items,
        ]);
    }

}
