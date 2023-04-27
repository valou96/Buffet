<?php

namespace App\Controller;

use App\Entity\Demande;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListDemandeNonConfirmeController extends AbstractController
{
    #[Route('/list/demande/non/confirme', name: 'app_list_demande_non_confirme')]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {

        $user = $doctrine->getRepository(User::class)->find($this->getUser());
        $mesDemandes = $doctrine->getRepository(Demande::class)->findBy(['user' => $this->getUser()]);



        return $this->render('list_demande_non_confirme/index.html.twig', [
            'demandes' => $mesDemandes,
            'user' => $user,
        ]);
    }

    #[Route('/list/demande/non/confirme', name: 'btn_conf')]
    public function conf(Request $request, ManagerRegistry $doctrine): Response
    {
        $demande = new demande();
        $user = $doctrine->getRepository(User::class)->find($this->getUser());
        $mesDemandes = $doctrine->getRepository(Demande::class)->findBy(['user' => $this->getUser()]);
        $demande->setDateConfirmation(\DateTime::createFromFormat('yyyy-mm-jj', 'now'));



        return $this->render('list_demande_non_confirme/index.html.twig', [
            'demandes' => $mesDemandes,
            'user' => $user,
        ]);
    }
}
