<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produit;
use Symfony\Component\HttpFoundation\Response;
use App\Form\ProduitType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Panier;

/**
 * @Route("/produit", name="produit_")
 */

class ProduitController extends AbstractController
{

    /**
     * @Route("/creer", name="creer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function creer(Request $request): Response
    {

        $produit = new Produit();
        $formulaire = $this->createForm(ProduitType::class, $produit);
        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            //traitement lié au champ fichier dans le formulaire
            $image = $produit->getImage();

            if ($image) {
                $fileName =  $produit->getNom() . "." . $image->guessExtension();

                try {
                    $image->move('upload/', $fileName);
                    $produit->setImage($fileName);
                } catch (Exception $exception) {
                    $produit->setImage(null);
                }
            }
            $gestionnaire = $this->getDoctrine()->getManager();
            $gestionnaire->persist($produit);
            $gestionnaire->flush();

            $this->addFlash('info', 'bravo');
            return $this->render('produit/voir.html.twig', [
                'produit' => $produit
            ]);
        }
        return $this->render('produit/creer.html.twig', [
            'formulaire' => $formulaire->createView()
        ]);
    }

    /**
     * @Route("/voir/{id}", name="voir", requirements={"id": "\d+"})
     */
    public function voir(Produit $produit): Response
    {
        return $this->render('produit/voir.html.twig', [
            'produit' => $produit
        ]);
    }

    /**
     * @Route("/liste", name="liste")
     */
    public function liste(): Response
    {
        $depot = $this->getDoctrine()->getRepository(Produit::class);
        $produits = $depot->findAll();
        return $this->render('produit/liste.html.twig', [
            'produits' => $produits
        ]);
    }

    /**
     * @Route("/ajouter/{id}", name="ajouter", requirements={"id":"\d+"})
     * @IsGranted("ROLE_USER")
     */
    public function ajouterPanier(Produit $produit)
    {
        $utilisateur = $this->getUser();
        $gestionnaire = $this->getDoctrine()->getManager();

        // Créer un Panier si l'utilisateur n'en as pas
        $panier = $utilisateur->getPanier();
        if ($panier === null) {
            $panier = new Panier();
            $panier->setUtilisateur($utilisateur);

            $gestionnaire->persist($panier);
        }
        $panier->addProduit($produit);
        $gestionnaire->flush();

        return $this->redirectToRoute("produit_liste");
    }

    /**
     * @Route("/retirer/{id}", name="retirer", requirements={"id":"\d+"})
     * @IsGranted("ROLE_USER")
     */
    public function supprimerArticleDuPanier(Produit $produit)
    {
        $panier = $this->getUser()->getPanier();
        if ($panier === null) {
            throw $this->createNotFoundException("Vous n'avez pas de panier !");
        }
        $panier->removeProduit($produit);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('utilisateur_panier');
    }
    /**
     * @Route("/supprimer/{id}", name="supprimer", requirements={"id":"\d+"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function supprimerProduit(Produit $produit)
    {
        $gestionnaire = $this->getDoctrine()->getManager();
        $gestionnaire->remove($produit);
        $gestionnaire->flush();

        return $this->redirectToRoute('produit_liste');
    }
}
