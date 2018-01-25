<?php
/**
 * Created by PhpStorm.
 * User: imoz
 * Date: 16/01/2018
 * Time: 20:01
 */

namespace App\Controller;

use App\Entity\Klantaccount;
use App\Entity\Klantadres;
use App\Entity\Klantgegeven;
use App\Entity\ObjectProduct;
use App\Entity\OptieProduct;
use App\Entity\Rijbewijs;
use App\Entity\Specificatie;
use App\Form\KlantaccountResetType;
use App\Form\KlantAccountType;
use App\Form\RegisterType;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

// De DefaultController is bedoeld voor alle routes die beschikbaar moeten zijn voor ongeregistreerde gebruikers
class DefaultController extends Controller
{
    // Controller voor de homepagina. Deze controller wordt gekoppeld aan een route in config/routing.yaml
    public function home()
    {
        // $this->render kan aangeroepen worden omdat deze templating-engine beschikbaar wordt gesteld door 'extends Controller'
        return $this->render('home.html.twig');
    }

    // Controller voor de contactpagina. Deze controller wordt gekoppeld aan een route in config/routing.yaml
    public function contact()
    {
        // $this->render kan aangeroepen worden omdat deze templating-engine beschikbaar wordt gesteld door 'extends Controller'
        return $this->render('pages/contact.html.twig');

    }

    public function forgetPassword()
    {
        return $this->render('pages/forgot-password.html.twig');
    }
    // Controller voor het verwerken van een vergeten wachtwoord. Dit is afkomstig van het formulier inde forgetPassword() functie hierboven
    public function forgetPasswordAction(Request $request, \Swift_Mailer $mailer)
    {
        // Haalt het email veld uit de POST variabelen
        $email = $request->request->get('email');
        // Checkt of er een emailadres is ingevult
        if($email){
            // Haal entity manager op beschikbaar gesteld door 'extends Controller'. En koppelt Klantacccount als het de geslecteerde entity Klantaccount::class
            $repository = $this->getDoctrine()->getRepository(Klantaccount::class);
            // Vraagt de gebruiker op met het opgegeven emailadres en krijgt het Klantaccount entiteit terug als een gebruiker gevonden wordt
            $user = $repository->getUserByEmail($email)[0];
            // Checkt of er een entiteit weer gekomen is
            if($user){
                // Stuur verificatie email weg
                $message = (new \Swift_Message('Wachtwoord herstellen - de Dissel'))
                    ->setFrom('beheerderdedissel@gmail.com')
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView(
                            'email/forget-password-email.html.twig', array(
                                'username' => $user->getUsername(),
                                'token' => $user->getForgetToken()
                            )
                        ),
                        'text/html'
                    )
                ;

                $mailer->send($message);
                // Stuurt de gebruiker terug met een succes-pagina met verder informatie
                return $this->render('pages/forget-password-success.html.twig');
            }
        } else{
            // Stuurt de gebruiker terug naar de 'wachtwoord-vergeten' pagina met een foutmelding
            return $this->render('pages/forgot-password.html.twig', array(
                'error' => '<i class="fas fa-exclamation-triangle"></i> Het opgegeven emailadres is niet geldig'
            ));
        }
    }

    // Controller voor de over ons pagina. Deze controller wordt gekoppeld aan een route in config/routing.yaml
    public function overons(){
        // $this->render kan aangeroepen worden omdat deze templating-engine beschikbaar wordt gesteld door 'extends Controller'
        return $this->render('pages/overons.html.twig');
    }

    // Controller voor het huidig aanbod pagina. Deze controller wordt gekoppeld aan een route in config/routing.yaml
    public function huidigaanbod(){
        $allowOrder = true;
        $objecten = $this->getDoctrine()->getRepository(ObjectProduct::class)->findAll();
        $specificaties = array();
        foreach ($objecten as $key => $object)  {
            $specificaties[$key] = $object->getSpecificatie();
        }
        return $this->render('pages/huidigaanbod.html.twig', array(
            'specificaties' => $specificaties,
            'objecten' => $objecten,
            'disabled' => !$allowOrder
        ));
    }

    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer)
    {
        // Initialiseer Entiteit om later te vullen met registratiegegevens
        $user = new Klantaccount();

        // Initialiseer back-end van formuli er
        $form = $this->createForm(RegisterType::class, $user);

        // Handel aanvraag af als het voldoet aan alle criteria
        $form->handleRequest($request);

        // Check of gegevens voldoen aan eisen voordat database-calls worden gedaan
        if ( $form->isSubmitted() && $form->isValid() ){

            // Codeer het wachtwoord met bcrypt encoder. Wachtwoord wordt tijdelijk plain opgeslagen in het user object. Dit wordt NIET uiteindelijk opgeslage in het database.
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());

            // Sla het gegenereerde wachtwoord op
            $user->setPassword($password);

            // Haal de Entity Manager op dat beschikbaar wordt gesteld door de 'extends Controller'
            $em = $this->getDoctrine()->getManager();

            // Zet de gebruiker in tijdelijke opslag, klaar voor opslag. Hierna zouden bijvoorbeeld meerdere entities worden toegevoegd om die vervolgens in een database call in het database te zetten.
            $em->persist($user);

            // Entity manager zet alle entities die gepersist werden in het database
            $em->flush();

            // Stuur verificatie email weg
            $message = (new \Swift_Message('Klantregistratie de Dissel'))
                ->setFrom('beheerderdedissel@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                    // templates/emails/registration.html.twig
                        'email/verification-email.html.twig', array(
                            'username' => $user->getUsername(),
                            'token' => $user->getVerificationToken()
                        )
                    ),
                    'text/html'
                )
            ;

            $mailer->send($message);

            // Gebruiker wordt herleid naar homepagina wanneer gebruiker succesvol is aangemaakt
            return $this->redirectToRoute('register_success');
        }
        // Laat het standaard registratie formulier zien als het formulier nog niet verstuurd is of niet valide is
        return $this->render(
            'pages/register.html.twig',
            array('form' => $form->createView())
        );
    }


    // Controller voor de succesvolle-registratie pagina
    public function registerSuccessAction()
    {
        // Stuurt de gebruiker naar de succesvolle-registratie pagina
        return $this->render('pages/register-success.html.twig', array(
            'message' => 'Uw ontvangt dadelijk een verificatie-email in uw postvak, deze stap is benodigd voor het plaatsen van bestellingen. Het is mogelijk dat de e-mail in uw spam-folder beland.'
        ));
    }
    // Controller voor het wachtwoord reset-proces (klikbare reset wachtwoord link in email)
    public function resetPassword($token, UserPasswordEncoderInterface $passwordEncoder, Request $request)
    {
        // Haalt de repository op die bij het KlantAccount entiteit hoort
        $repository = $this->getDoctrine()->getRepository(Klantaccount::class);

        // Haalt de gebruiker op met het gegeven forgetToken
        $user = $repository->getUserByForgetToken($token);

        // Checkt of er een gebruiker terug is gestuurd
        if($user){

            // Maakt een formulier aan voor de ontvangen entity
            $form = $this->createForm(KlantaccountResetType::class, $user[0]);

            // Handel POST requests af
            $form->handleRequest($request);

            // Check of gegevens correct zijn ingevult en er een POST submit is gedaan
            if($form->isSubmitted() && $form->isValid() ){

                // Codeer het wachtwoord met bcrypt encoder. Wachtwoord wordt tijdelijk plain opgeslagen in het user object. Dit wordt NIET uiteindelijk opgeslage in het database.
                $password = $passwordEncoder->encodePassword($user[0], $user[0]->getPlainPassword());

                // Voer de verandering door aan het klantaccount-enteit
                $user[0]->setPassword($password);

                // Zet een nieuwe reset-token voor een volgende wachtwoord-reset
                $user[0]->setForgetToken(random_int(1, 200000000000));

                // Haal entity-manager op beschikbaar gesteld door 'extends Controller'
                $em = $this->getDoctrine()->getManager();

                // Zet de bewerkte gebruiker in tijdelijke opslag voor databasebewerkingen uit te voeren
                $em->persist($user[0]);

                // Voer alle databasebewerkingen die in de wachtrij stonden uit
                $em->flush();

                // Stuur de gebruiker door naar een Wachtwoord-gewijzigd pagina
                return $this->redirectToRoute('reset_password_success');
            }

            // Stuur de gebruiker terug naar het formulier
            return $this->render('pages/reset-password.html.twig', array(
                'form' => $form->createView()
            ));

        }
    }

    public function resetPasswordSuccess()
    {
        return $this->render('pages/reset-password-success.html.twig');
    }

    public function verifyAction($verificationToken)
    {
        // Haal entiteit-manager op beschikbaar gesteld door 'extends Controller' en geeft Klantaccount als repository, roept de custom functie in de repository aan en krijgt een klant / geen klant terug.
        $user = $this->getDoctrine()->getRepository(Klantaccount::class)->getUserByVerifictionToken($verificationToken);
        // Check of $user een Klantaccount is
        if($user){
            if( $user[0]->getVerificationToken() == $verificationToken ){
                // Zet de gebruiker-verificatie-status op geverifieerd
                $user[0]->setIsVerified(true);
                // Haal entiteits-manager op
                $em = $this->getDoctrine()->getManager();
                // Zet de het bewerkte klant-account in tijdelijke opslag zodat e.v.t. nog meer databasebewerkingen klaargezet kunnen worden
                $em->persist($user[0]);
                // Voor alle databasebewerkingen door
                $em->flush();
                // Stuur gebruiker naar verificatie-success pagina
                return $this->render('pages/verification-success.html.twig', array(
                    'message' => 'Uw kunt nu bestellingen plaatsen op de website door in te loggen met uw klantaccount'
                ));
            }
        } else {
            // Stuur gebruiker naar verificaitie-gefaald pagina
            return $this->render('pages/verification-failed.html.twig', array(
                'message' => 'Neem a.u.b. contact op met onze systeembeheerder voor een snelle afhandeling.'
            ));
        }
    }

    public function viewObject($id)
    {
        $object = $this->getDoctrine()->getRepository(ObjectProduct::class)->find($id);
        $specificaties = $object->getSpecificatie();
        $query = $this->getDoctrine()->getRepository(OptieProduct::class)->createQueryBuilder('qb')
            ->where('qb.optieDatumTerug < CURRENT_DATE()')
            ->getQuery();
        $opties = $query->execute();
        return $this->render('pages/view-object.html.twig', array(
            'object' => $object,
            'specificatie' => $specificaties,
            'opties' => $opties
        ));
    }

    // Route voor het inloggen van AnonymousUser gebruikers
    public function loginAction(Request $request, AuthenticationUtils $authUtils)
    {
        // Haal login errors op als deze aanwezig zijn
        $error = $authUtils->getLastAuthenticationError();

        // Haal het laatst ingevulde gebruikersnaam op als deze aanwezig is
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('pages/login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error
        ));

    }
}
