<?php

namespace App\Controller\Gestionnaire;

use App\Entity\Complement;
use App\Form\ComplementType;
use App\Repository\ComplementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class GestionnaireComplementController extends AbstractController
{
    /**
     * @var ComplementRepository
     */
    private ComplementRepository $repository;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;
    public function __construct(ComplementRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;

        $this->em = $em;
    }

    /**
     * @Route ("/gestionnaire/complement", name="gestionnaire.complement.index")
     * @return Response
     */
    public  function index(): Response
    {
        $complements = $this->repository->findAll();
        return $this->render('gestionnaire/complement/index.html.twig', compact('complements'));
    }

    /**
     * @Route ("/gestionnaire/complement/create", name="gestionnaire.complement.new")
     * @param Request $request
     * @param SluggerInterface $slugger
     * @return Response
     */
    public function new(Request $request, SluggerInterface $slugger):Response
    {
        $complement = new Complement();
        $form = $this->createForm(ComplementType::class, $complement);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $photo = $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('complement_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $complement->setImage($newFilename);
            }

            $this->em->persist($complement);
            $this->em->flush();
            $this->addFlash('success','Ajout avec succés');
            return $this->redirectToRoute('gestionnaire.complement.index');
        }
        return $this->render('gestionnaire/complement/new.html.twig',[
            'complement' => $complement,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route ("/gestionnaire/complement/edit/{id}", name="gestionnaire.complement.edit", methods={"GET|POST"})
     * @param Complement $complement
     * @param Request $request
     * @param SluggerInterface $slugger
     * @return Response
     */
    public function edit(Complement $complement, Request $request, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ComplementType::class, $complement);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $photo = $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('complement_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $complement->setImage($newFilename);
            }
            $this->em->flush();
            $this->addFlash('success','Modication avec succés');
            return $this->redirectToRoute('gestionnaire.complement.index');
        }

        return $this->render('gestionnaire/complement/edit.html.twig',[
            'complement' => $complement,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route ("/gestionnaire/complement/delete/{id}", name="gestionnaire.complement.delete", methods={"DELETE"})
     * @param Complement $complement
     * @param Request $request
     * @return Response
     */
    public function delete(Complement $complement, Request $request):Response
    {
        if($this->isCsrfTokenValid('delete' . $complement->getId(), $request->get('_token')))
        {
            $this->em->remove($complement);
            $this->em->flush();
            $this->addFlash('success','Supression avec succés');
        }

        return $this->redirectToRoute('gestionnaire.complement.index');

    }

}
