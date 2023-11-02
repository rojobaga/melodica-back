<?php

namespace App\Controller\Backoffice;

use App\Entity\Band;
use App\Form\BandType;
use DateTimeImmutable;
use App\Entity\Address;
use App\Repository\BandRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("back/band", name="back_band_")
 */
class BandController extends AbstractController
{
    /**
     * @Route("/", name="list", methods={"GET"})
     */
    public function list(BandRepository $bandRepository): Response
    {
        return $this->render('band/list.html.twig', [
            'bands' => $bandRepository->findAll(),
        ]);
    }

    /**
     * @Route("/create", name="create", methods={"GET", "POST"})
     */
    public function create(Request $request, BandRepository $bandRepository): Response
    {
        $band = new Band();
        
        $band->setCreatedAt(new DateTimeImmutable());
        $form = $this->createForm(BandType::class, $band);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bandRepository->add($band, true);
            $this->addFlash('success', 'Groupe ajouté !');
            return $this->redirectToRoute('back_band_list', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('band/create.html.twig', [
            'band' => $band,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="show", methods={"GET"})
     */
    public function show(Band $band): Response
    {
        return $this->render('band/show.html.twig', [
            'band' => $band,
        ]);
    }

    /**
     * @Route("/{id<\d+>}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Band $band, BandRepository $bandRepository): Response
    {
        $form = $this->createForm(BandType::class, $band);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bandRepository->add($band, true);
            $this->addFlash('success', 'Groupe modifié !');
            return $this->redirectToRoute('back_band_list', [], Response::HTTP_SEE_OTHER);
        }
       
        return $this->renderForm('band/edit.html.twig', [
            'band' => $band,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, Band $band, BandRepository $bandRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$band->getId(), $request->request->get('_token'))) {
            $bandRepository->remove($band, true);
        }
        $this->addFlash('success', 'Groupe supprimé !');
        return $this->redirectToRoute('back_band_list', [], Response::HTTP_SEE_OTHER);
    }
}
