<?php
/**
 * Created by PhpStorm.
 * User: imoz
 * Date: 20/01/2018
 * Time: 13:56
 */

namespace App\Controller;

use App\Entity\Klantaccount;
use App\Entity\Klantadres;
use App\Entity\Klantgegeven;
use App\Entity\KlantOrder;
use App\Entity\ObjectProduct;
use App\Entity\Rijbewijs;
use App\Form\AdresType;
use App\Form\EditCredentialsType;
use App\Form\DetailsType;
use App\Form\RijbewijsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

// De UserController is bedoeld voor alle routes die beschikbaar moeten zijn voor klant-gebruikers
class UserController extends Controller
{


    public function overviewOrders()
    {

    }

    public function viewOrder($order)
    {

    }

    public function viewUserDetails(UserInterface $user)
    {
        $klant = $this->getDoctrine()->getRepository(Klantaccount::class)->find($user);

        $klantgegevens = $user->getKlantPersoonlijkeGegevens();
        if (!$klantgegevens) {
            $klantgegevens = new Klantgegeven();
        }

        $rijbewijs = $klantgegevens->getRijbewijs();
        if ( !$rijbewijs ){
            $rijbewijs = new Rijbewijs();
        }

        $klantNAW = $klantgegevens->getKlantNAW();
        if ( !$klantNAW ) {
            $klantNAW = new Klantadres();
        }

        return $this->render('user/user-overview.html.twig', array(
            'klant' => $klant,
            'klantGegeven' => $klantgegevens,
            'klantNAW' => $klantNAW,
            'rijbewijs' => $rijbewijs
        ));
    }

    public function editUserCredentials(UserInterface $user, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $klant = $this->getDoctrine()->getRepository(Klantaccount::class)->find($user);
        $form  = $this->createForm(EditCredentialsType::class, $klant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($klant, $klant->getPlainPassword());
            $klant->setPassword($password);
            $em = $this->getDoctrine()->getManager();
            $em->persist($klant);
            $em->flush();
            return $this->redirectToRoute('user_view_details');
        }
        return $this->render('user/edit-user-credentials.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function editUserDetails(UserInterface $user, Request $request)
    {
        $klant = $this->getDoctrine()->getRepository(Klantaccount::class)->find($user);
        $details = $klant->getKlantPersoonlijkeGegevens();
        if( !$details ){
            $details = new Klantgegeven();
            $klant->setKlantPersoonlijkeGegevens($details);
        }
        $adres = $details->getKlantNAW();
        $detailsForm  = $this->createForm( DetailsType::class, $details, array(
            'attr' => array(
                'class' => 'default_form'
            )
        ));
        if (!$adres){
            $adres = new Klantadres();
            $details->setKlantNAW($adres);
        }
        $adresForm = $this->createForm( AdresType::class, $adres);
        $detailsForm->handleRequest($request);
        $adresForm->handleRequest($request);
        if ($detailsForm->isSubmitted() && $detailsForm->isValid() && $adresForm->isSubmitted() && $adresForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adres);
            $em->persist($details);
            $em->flush();
            return $this->redirectToRoute('user_view_details');
        };
        return $this->render('user/edit-user-details.html.twig', array(
            'detailsForm' => $detailsForm->createView(),
            'adresForm' => $adresForm->createView()
        ));
    }

    public function editUserLicense(Request $request, UserInterface $user)
    {
        $klant = $this->getDoctrine()->getRepository(Klantaccount::class)->find($user);
        $klantGegevens = $klant->getKlantPersoonlijkeGegevens();
        $rijbewijs = $klantGegevens->getRijbewijs();
        if (!$rijbewijs) {
            $rijbewijs = new Rijbewijs();
        }
        $form = $this->createForm(RijbewijsType::class, $rijbewijs);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if( $rijbewijs->getRijbewijsGecontroleerd() ){
                $rijbewijs->setRijbewijsGecontroleerd(false);
            }
            $klantGegevens->setRijbewijs($rijbewijs);
            $em = $this->getDoctrine()->getManager();
            $em->persist($klantGegevens);
            $em->persist($rijbewijs);
            $em->flush();
            return $this->redirectToRoute('user_view_details');
        }
        return $this->render('user/edit-user-rijbewijs.html.twig', array(
            'form' => $form->createView(),
            ''
        ));
    }

    public function addOrder($objectId)
    {
        $object = $this->getDoctrine()->getRepository(ObjectProduct::class)->find($objectId);
        $order = new KlantOrder();
        $order->setObjectProduct($object);
        return $this->render('user/new-order.html.twig', array(
            'object' => $object
        ));
    }

    public function addOrderWithOptions($optionsArr){

    }
}