<?php

namespace App\Controller;

use App\Entity\Demande;
use App\Entity\User;
use App\Form\DemandeType;
use App\Repository\DemandeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SaisirDemandeController extends AbstractController
{
    #[Route('/saisirdemande', name: 'app_saisir_demande')]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $message = "Votre statut est bloqué, vous ne pouvez pas créer de demande";
        $demande = new Demande();
        $user = $doctrine->getRepository(User::class)->find($this->getUser());
        $demande->setUser($user);
        $form = $this->createForm(DemandeType::class, $demande);
        $form->handleRequest($request);
        if ($user->isEstBloque() == false) {
            if ($form->isSubmitted() && $form->isValid()) {
                $doctrine->getRepository(Demande::class)->save($demande, true);

                return $this->redirectToRoute('app_mes_demandes', [], Response::HTTP_SEE_OTHER);
            }
        }
        else{
            return $this->render('mes_demandes/index.html.twig', ['message' => $message, 'user' => $user, 'demandes' => $demande]);
        }

        return $this->render('saisir_demande/index.html.twig', [
            'demande' => $demande,
            'form' => $form,

        ]);
    }
}
