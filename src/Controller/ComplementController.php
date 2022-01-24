<?php

namespace App\Controller;

use App\Entity\Complement;
use App\Repository\ComplementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ComplementController extends AbstractController
{
    /**
     * @var Complement
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    public function __construct(ComplementRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }



    /**
     * @Route ("/complement", name="complement.index")
     * @return Response
     */
    public function index(ComplementRepository $repository, PaginatorInterface $paginator, Request $request):Response
    {

        $complement = $paginator->paginate(
            $this->repository->findAllComplementQuery(),
            $request->query->getInt('page','1'),
            '12'
        );


        return  $this->render('complement/index.html.twig', [
            'current_menu' => 'complements',
            'complements' => $complement
        ]);
    }

    /**
     * @Route ("/complement/{slug}-{id}", name="complement.show", requirements={"slug": "[a-z0-9\-]*"})
     * @return Response
     */
    public  function  show(Complement $complement, string $slug) :Response
    {
        if($complement->getSlug() !== $slug)
        {
            return $this->redirectToRoute('complement.show', [
                'id' =>$complement->getId(),
                'slug'=>$complement->getSlug()
            ], 301);
        }
        return $this->render('complement/show.html.twig', [
            'complement' => $complement,
            'current_menu' => 'complements'
        ]);
    }
}
