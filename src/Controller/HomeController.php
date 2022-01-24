<?php

namespace App\Controller;

use App\Repository\BurgerRepository;
use App\Repository\MenuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @Route ("/", name="home")
     * @param BurgerRepository $repository
     * @return Response
     */
    public function index(BurgerRepository $repository):Response
    {
        $burgers = $repository->findLatestBurger();
        return $this->render('pages/home.html.twig', [
            'burgers' => $burgers
        ]);

    }

}