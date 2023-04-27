<?php

namespace App\Controller;

use App\Entity\TypePiece;
use App\Form\TypePieceType;
use App\Repository\TypePieceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/type/piece')]
#[IsGranted('ROLE_USER')]
class TypePieceController extends AbstractController
{
    #[Route('/', name: 'app_type_piece_index', methods: ['GET'])]
    public function index(TypePieceRepository $typePieceRepository): Response
    {
        return $this->render('type_piece/index.html.twig', [
            'type_pieces' => $typePieceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_type_piece_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TypePieceRepository $typePieceRepository): Response
    {
        $typePiece = new TypePiece();
        $form = $this->createForm(TypePieceType::class, $typePiece, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $typePieceRepository->save($typePiece, true);

            return $this->redirectToRoute('app_type_piece_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('type_piece/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_piece_show', methods: ['GET'])]
    public function show(TypePiece $typePiece): Response
    {
        return $this->render('type_piece/show.html.twig', [
            'type_piece' => $typePiece,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_type_piece_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TypePiece $typePiece, TypePieceRepository $typePieceRepository): Response
    {
        $form = $this->createForm(TypePieceType::class, $typePiece);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $typePieceRepository->save($typePiece, true);

            return $this->redirectToRoute('app_type_piece_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('type_piece/edit.html.twig', [
            'type_piece' => $typePiece,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_piece_delete', methods: ['POST'])]
    public function delete(Request $request, TypePiece $typePiece, TypePieceRepository $typePieceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typePiece->getId(), $request->request->get('_token'))) {
            $typePieceRepository->remove($typePiece, true);
        }

        return $this->redirectToRoute('app_type_piece_index', [], Response::HTTP_SEE_OTHER);
    }
}
