<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Utilisateur;
use Symfony\Component\HttpFoundation\Response;
use App\Form\ProduitType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Panier;
use App\Form\UtilisateurType;

/**
 * @Route("/utilisateur", name="utilisateur_")
 */

class UtilisateurController extends AbstractController
{

    /**
     * @Route("/panier", name="panier")
     * @IsGranted("ROLE_USER")
     */
    public function panier(): Response
    {
        $utilisateur = $this->getUser();
        return $this->render("utilisateur/panier.html.twig", ["utilisateur" => $utilisateur]);
    }

    /**
     * @Route("/editer/{id}", name="editer", requirements={"id":"\d+"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function editer(Utilisateur $utilisateur, Request $requete): Response
    {
        $formulaire = $this->createForm(UtilisateurType::class, $utilisateur);

        $formulaire->handleRequest($requete);
        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            $gestionnaire = $this->getDoctrine()->getManager();
            $gestionnaire->flush();
            return $this->redirectToRoute("produit_liste");
        }




        return $this->render("utilisateur/editer.html.twig", ["formulaire" => $formulaire->createView(), "utilisateur" => $utilisateur]);
    }
}
