<?php

namespace App\Controller;

use App\Entity\Burger;
use App\Repository\BurgerRepository;
//use Doctrine\Persistence\ObjectManager;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;


class BurgerController extends AbstractController
{

    /**
     * @var BurgerRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    public function __construct(BurgerRepository $repository, EntityManagerInterface $em)
    {
       $this->repository = $repository;
       $this->em = $em;
    }



    /**
     * @Route ("/burger", name="burger.index")
     * @return Response
     */
    public function index(BurgerRepository $repository, PaginatorInterface $paginator, Request $request):Response
    {

        $burger = $paginator->paginate(
            $this->repository->findAllBurgerQuery(),
            $request->query->getInt('page','1'),
            '12'
        );


        return  $this->render('burger/index.html.twig', [
            'current_menu' => 'burgers',
            'burgers' => $burger
        ]);
    }

    /**
     * @Route ("/burger/{slug}-{id}", name="burger.show", requirements={"slug": "[a-z0-9\-]*"})
     * @return Response
     */
    public  function  show(Burger $burger, string $slug) :Response
    {
        if($burger->getSlug() !== $slug)
        {
            return $this->redirectToRoute('burger.show', [
                'id' =>$burger->getId(),
                'slug'=>$burger->getSlug()
            ], 301);
        }
        return $this->render('burger/show.html.twig', [
            'burger' => $burger,
            'current_menu' => 'burgers'
        ]);
    }

}