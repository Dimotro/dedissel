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
use App\Entity\KlantOrder;
use App\Entity\ObjectProduct;
use App\Entity\ObjectProductPeriod;
use App\Entity\OptieProduct;
use App\Entity\OptionProductPeriod;
use App\Entity\Rijbewijs;
use App\Entity\Specificatie;
use App\Form\CaptchaType;
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

            $username = $user->getUsername();
            $found = $this->getDoctrine()->getRepository(Klantaccount::class)->findBy(array(
                'username' => $username
            ));

            if ($found) {
                $random = random_int(1,200);
                $user->setUsername($username . $random);
                return $this->render(
                    'pages/register.html.twig',
                    array(
                        'form' => $form->createView(),
                        'usernameBezet' => true
                    )
                );
            }

            $fountEmail = $this->getDoctrine()->getRepository(Klantaccount::class)->findBy(array(
                'username' => $username
            ));

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
            array(
                'form' => $form->createView(),
            )
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
        $opties = $this->getDoctrine()->getRepository(OptionProductPeriod::class)->getAvailibleOptions();
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
        $form = $this->createForm(CaptchaType::class, array(), array(
            'action' => $this->generateUrl('login_page')
        ));

        // Haal het laatst ingevulde gebruikersnaam op als deze aanwezig is
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('pages/login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
            'form' => $form->createView()
        ));

    }

    // Route voor het aanmaken van testdata
    public function testData(UserPasswordEncoderInterface $passwordEncoder)
    {
        $klant = new Klantaccount();
        $klant->setUsername('vulgebruiker');
        $klant->setPassword($passwordEncoder->encodePassword($klant, 'lollol'));
        $klant->setEmail('testgebruiker@lol.nl');
        $klant->setIsVerified(true);
        $klant->setIsActive(true);

        $klantgegeven = new Klantgegeven();
        $klantgegeven->setKlantVoorletters('M.D.');
        $klantgegeven->setKlantVoornaam('Misha');
        $klantgegeven->setKlantTussenvoegsel('van de');
        $klantgegeven->setKlantAchternaam('Karas');
        $klantgegeven->setKlantMobiel('0640228523');
        $klantgegeven->setKlantTelefoon('0548615820');

        $klantNAW = new Klantadres();
        $klantNAW->setKlantStraat('Schoolstraat');
        $klantNAW->setKlantHuisnummer('24');
        $klantNAW->setKlantWoonplaats('Almelo');

        $klantRijbewijs = new Rijbewijs();
        $klantRijbewijs->setRijbewijsnummer(432443234);
        $klantRijbewijs->setRijbewijsGeldigtot(new \DateTime('now + 3 years'));
        $klantRijbewijs->setRijbewijsType(
            array(
                'A' => true,
                'A1' => true,
                'A2' => true,
                'AM' => true,
                'B' => true,
                'BE' => true,
                'BPlus' => true,
                'C' => true,
                'CE' => true,
                'C1' => true,
                'C1E' => true,
                'D' => true,
                'DE' => true,
                'D1' => true,
                'D1E' => true,
                'T' => true,
            )
        );

        $klantgegeven->setKlantNAW($klantNAW);
        $klantgegeven->setRijbewijs($klantRijbewijs);
        $klant->setKlantPersoonlijkeGegevens($klantgegeven);

        $object1 = new ObjectProduct();
        $object1->setBeschikbaarheid(true);
        $object1->setObjOmschrijving("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum");
        $object1->setChassisnummer("7DR239047111147");
        $object1->setFotos(array('0e5769552be7f881cced0cf89a906154.png'));
        $object1->setKenteken("WP-12-AS");
        $object1->setObjNaam("Hobby 495 UL");
        $object1->setObjType("Caravan");
        $object1->setPrijs(60);

        $optie1 = new OptieProduct();
        $optie1->setOptieTitel('Voortent');
        $optie1->setOptieOmschrijving('Mooie voortent');
        $optie1->setOptiePrijs('100');

        $specificatie1 = new Specificatie();
        $specificatie1->setMerk("Hobby");
        $specificatie1->setType("495 UL");
        $specificatie1->setBouwjaar(2017);
        $specificatie1->setMassaInventaris(1350);
        $specificatie1->setMassaMax(1550);
        $specificatie1->setLengteTot(713);
        $specificatie1->setLengteOpbouw(595);
        $specificatie1->setHoogte(262);
        $specificatie1->setRijbewijsBenodigd("BE");
        $object1->setSpecificatie($specificatie1);

        //2e row
        $object2 = new ObjectProduct();
        $object2->setBeschikbaarheid(true);
        $object2->setObjOmschrijving("Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur");
        $object2->setChassisnummer("7DR239047112292");
        $object2->setFotos(array('0e5769552be7f881cced0cf89a906154.png'));
        $object2->setKenteken("WD-55-TG");
        $object2->setObjNaam("Hobby 495 UL");
        $object2->setObjType("Caravan");
        $object2->setPrijs(50);

        $optie2 = new OptieProduct();
        $optie2->setOptieTitel('Bijzettent');
        $optie2->setOptieOmschrijving('Mooie bijzettent');
        $optie2->setOptiePrijs('75');

        $specificatie2 = new Specificatie();
        $specificatie2->setMerk("Hobby");
        $specificatie2->setType("495 UL");
        $specificatie2->setBouwjaar(2015);
        $specificatie2->setMassaInventaris(1350);
        $specificatie2->setMassaMax(1500);
        $specificatie2->setLengteTot(713);
        $specificatie2->setLengteOpbouw(595);
        $specificatie2->setHoogte(262);
        $specificatie2->setRijbewijsBenodigd("BE");

        $object2->setSpecificatie($specificatie2);

        //3e row
        $object3 = new ObjectProduct();
        $object3->setBeschikbaarheid(true);
        $object3->setObjOmschrijving("But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise, except to obtain some advantage from it? But who has any right to find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces no resultant pleasure?");
        $object3->setChassisnummer("7DR239047233162");
        $object3->setFotos(array('0e5769552be7f881cced0cf89a906154.png'));
        $object3->setKenteken("WL-23-SD");
        $object3->setObjNaam("Hobby 460 LU");
        $object3->setObjType("Caravan");
        $object3->setPrijs(40);

        $optie3 = new OptieProduct();
        $optie3->setOptieTitel('Windscherm');
        $optie3->setOptieOmschrijving('Mooie windscherm');
        $optie3->setOptiePrijs('20');

        $specificatie3 = new Specificatie();
        $specificatie3->setMerk("Hobby");
        $specificatie3->setType("460 LU");
        $specificatie3->setBouwjaar(2013);
        $specificatie3->setMassaInventaris(1100);
        $specificatie3->setMassaMax(1350);
        $specificatie3->setLengteTot(661);
        $specificatie3->setLengteOpbouw(550);
        $specificatie3->setHoogte(260);
        $specificatie3->setRijbewijsBenodigd("BE");

        $object3->setSpecificatie($specificatie3);


        //van hier nog invullen van data
        //4e row
        $object4 = new ObjectProduct();
        $object4->setBeschikbaarheid(true);
        $object4->setObjOmschrijving("At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat");
        $object4->setChassisnummer("7DR239047119811");
        $object4->setFotos(array('0e5769552be7f881cced0cf89a906154.png'));
        $object4->setKenteken("WG-13-BM");
        $object4->setObjNaam("Hobby 495 UL");
        $object4->setObjType("Caravan");
        $object4->setPrijs(60);

        $optie4 = new OptieProduct();
        $optie4->setOptieTitel('Barbecue');
        $optie4->setOptieOmschrijving('Mooie barbecue');
        $optie4->setOptiePrijs('30');

        $specificatie4 = new Specificatie();
        $specificatie4->setMerk("Hobby");
        $specificatie4->setType("495 UL");
        $specificatie4->setBouwjaar(2018);
        $specificatie4->setMassaInventaris(1350);
        $specificatie4->setMassaMax(1550);
        $specificatie4->setLengteTot(713);
        $specificatie4->setLengteOpbouw(595);
        $specificatie4->setHoogte(260);
        $specificatie4->setRijbewijsBenodigd("BE");

        $object4->setSpecificatie($specificatie4);


        //5e row
        $object5 = new ObjectProduct();
        $object5->setBeschikbaarheid(true);
        $object5->setObjOmschrijving("At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat");
        $object5->setChassisnummer("7DR239047511206");
        $object5->setFotos(array('0e5769552be7f881cced0cf89a906154.png'));
        $object5->setKenteken("WG-38-TY");
        $object5->setObjNaam("Hobby 460 LU");
        $object5->setObjType("Caravan");
        $object5->setPrijs(40);

        $optie5 = new OptieProduct();
        $optie5->setOptieTitel('Skottelbraai');
        $optie5->setOptieOmschrijving('Mooie skottelbraai');
        $optie5->setOptiePrijs('50');

        $specificatie5 = new Specificatie();
        $specificatie5->setMerk("Hobby");
        $specificatie5->setType("460 LU");
        $specificatie5->setBouwjaar(2013);
        $specificatie5->setMassaInventaris(1250);
        $specificatie5->setMassaMax(1350);
        $specificatie5->setLengteTot(661);
        $specificatie5->setLengteOpbouw(550);
        $specificatie5->setHoogte(260);
        $specificatie5->setRijbewijsBenodigd("BE");

        $object5->setSpecificatie($specificatie5);


        //6e row
        $object6 = new ObjectProduct();
        $object6->setBeschikbaarheid(true);
        $object6->setObjOmschrijving("On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, so blinded by desire, that they cannot foresee the pain and trouble that are bound to ensue; and equal blame belongs to those who fail in their duty through weakness of will, which is the same as saying through shrinking from toil and pain. These cases are perfectly simple and easy to distinguish. In a free hour, when our power of choice is untrammelled and when nothing prevents our being able to do what we like best, every pleasure is to be welcomed and every pain avoided. But in certain circumstances and owing to the claims of duty or the obligations of business it will frequently occur that pleasures have to be repudiated and annoyances accepted. The wise man therefore always holds in these matters to this principle of selection: he rejects pleasures to secure other greater pleasures, or else he endures pains to avoid worse pains");
        $object6->setChassisnummer("7DR239047114003");
        $object6->setFotos(array('0e5769552be7f881cced0cf89a906154.png'));
        $object6->setKenteken("WX-75-22");
        $object6->setObjNaam("Hobby 460 LU");
        $object6->setObjType("Caravan");
        $object6->setPrijs(40);

        $optie6 = new OptieProduct();
        $optie6->setOptieTitel('Televiesietoestel, schotelantenne en abonnement');
        $optie6->setOptieOmschrijving('Mooie televisietoestel, schotelantenne en abonnement');
        $optie6->setOptiePrijs('90');

        $specificatie6 = new Specificatie();
        $specificatie6->setMerk("Hobby");
        $specificatie6->setType("460 LU");
        $specificatie6->setBouwjaar(2013);
        $specificatie6->setMassaInventaris(1250);
        $specificatie6->setMassaMax(1350);
        $specificatie6->setLengteTot(661);
        $specificatie6->setLengteOpbouw(550);
        $specificatie6->setHoogte(260);
        $specificatie6->setRijbewijsBenodigd("BE");

        $object6->setSpecificatie($specificatie6);


        //7e row
        $object7 = new ObjectProduct();
        $object7->setBeschikbaarheid(true);
        $object7->setObjOmschrijving("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi pellentesque, nibh eget facilisis pellentesque, lorem purus ultricies nibh, quis rhoncus dui purus ac elit. Vivamus facilisis nibh ac augue volutpat, tincidunt pulvinar orci pellentesque. Etiam neque sapien, finibus non varius in, sagittis eget ligula. Suspendisse massa urna, eleifend nec sodales sit amet, finibus a odio. Vivamus sagittis lectus eget tristique tempus. Donec fermentum placerat molestie. Fusce tincidunt ante quam, et sodales nibh porttitor nec. Nunc mollis leo mi, at venenatis ligula pretium cursus. Duis dapibus tristique nunc id lacinia. Mauris quis justo vel nulla sollicitudin euismod. Fusce non neque at orci ullamcorper ultricies. Praesent condimentum ut nulla vitae rutrum.");
        $object7->setChassisnummer("7BMDF239047114003");
        $object7->setFotos(array('0e5769552be7f881cced0cf89a906154.png'));
        $object7->setKenteken("BC-113-P");
        $object7->setObjNaam("Optima V60GF");
        $object7->setObjType("Camper");
        $object7->setPrijs(100);

        $optie7 = new OptieProduct();
        $optie7->setOptieTitel('Uitbreidingsset servies en bestek');
        $optie7->setOptieOmschrijving('Mooie uitbreidingsset servies en bestek');
        $optie7->setOptiePrijs('5');

        $specificatie7 = new Specificatie();
        $specificatie7->setMerk("Optima");
        $specificatie7->setType("V60GF");
        $specificatie7->setBouwjaar(2015);
        $specificatie7->setMassaInventaris(2900);
        $specificatie7->setMassaMax(3500);
        $specificatie7->setLengteTot(600);
        $specificatie7->setLengteOpbouw(430);
        $specificatie7->setHoogte(270);
        $specificatie7->setRijbewijsBenodigd("BE");

        $object7->setSpecificatie($specificatie7);


        //8e row
        $object8 = new ObjectProduct();
        $object8->setBeschikbaarheid(true);
        $object8->setObjOmschrijving("Aliquam vel sodales orci. Nam nec blandit ipsum. Morbi nulla enim, pulvinar vel sodales sit amet, condimentum sed nulla. Donec eleifend arcu id enim gravida ultricies. Sed ac posuere tortor. Duis tempor ante eu elit imperdiet, a volutpat sem ultricies. Quisque quis elementum ex. Donec non consequat nibh. Aliquam cursus euismod ipsum. Aliquam erat volutpat. Donec ultricies a libero ut sollicitudin. Vivamus dictum porttitor neque, non congue ex imperdiet et. Nam nec lectus quis urna egestas ultricies non sed nisi. Nam vulputate tortor risus, ut malesuada odio tempus sed. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Mauris lorem tellus, luctus vitae ornare vel, tempor at eros.");
        $object8->setChassisnummer("7BMDF239042148800");
        $object8->setFotos(array('0e5769552be7f881cced0cf89a906154.png'));
        $object8->setKenteken("BD-287-T");
        $object8->setObjNaam("Optima V60GF");
        $object8->setObjType("Camper");
        $object8->setPrijs(115);

        $optie8 = new OptieProduct();
        $optie8->setOptieTitel('Annuleringsverzekering');
        $optie8->setOptieOmschrijving('Een top annuleringsverzekering');
        $optie8->setOptiePrijs('50');

        $specificatie8 = new Specificatie();
        $specificatie8->setMerk("Optima");
        $specificatie8->setType("V60GF");
        $specificatie8->setBouwjaar(2017);
        $specificatie8->setMassaInventaris(2900);
        $specificatie8->setMassaMax(3500);
        $specificatie8->setLengteTot(600);
        $specificatie8->setLengteOpbouw(430);
        $specificatie8->setHoogte(270);
        $specificatie8->setRijbewijsBenodigd("B");

        $object8->setSpecificatie($specificatie8);



        //row 9
        $object9 = new ObjectProduct();
        $object9->setBeschikbaarheid(true);
        $object9->setObjOmschrijving("Donec ac erat vel dolor egestas fringilla. Nam id erat aliquam, scelerisque mi vel, aliquet tellus. Cras venenatis, eros sed bibendum hendrerit, turpis enim placerat magna, sit amet porttitor nisl lacus sit amet ligula. Suspendisse bibendum accumsan ex, in porttitor sem. Aenean tincidunt lectus quis suscipit malesuada. Curabitur ornare ante est, vitae pretium velit mollis a. In rutrum purus vestibulum scelerisque ultricies. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam non arcu ut nulla tempus posuere non nec lacus. Aenean eu dui eget lacus interdum pellentesque nec nec urna. Donec quis orci vel ipsum scelerisque bibendum nec at nisi. Integer gravida elementum ligula quis malesuada. Vivamus quis elit vitae tellus porttitor volutpat vitae vel justo.");
        $object9->setChassisnummer("7BMDG239047112297");
        $object9->setFotos(array('0e5769552be7f881cced0cf89a906154.png'));
        $object9->setKenteken("DV-441-K");
        $object9->setObjNaam("Optima T70E");
        $object9->setObjType("Camper");
        $object9->setPrijs(115);

        $optie9 = new OptieProduct();
        $optie9->setOptieTitel('Inboedelverzekering');
        $optie9->setOptieOmschrijving('Een top inboedelverzekering');
        $optie9->setOptiePrijs('40');

        $specificatie9 = new Specificatie();
        $specificatie9->setMerk("Optima");
        $specificatie9->setType("T70E");
        $specificatie9->setBouwjaar(2015);
        $specificatie9->setMassaInventaris(2900);
        $specificatie9->setMassaMax(1350);
        $specificatie9->setLengteTot(738);
        $specificatie9->setLengteOpbouw(510);
        $specificatie9->setHoogte(270);
        $specificatie9->setRijbewijsBenodigd("B");

        $object9->setSpecificatie($specificatie9);


        //row 10
        $object10 = new ObjectProduct();
        $object10->setBeschikbaarheid(true);
        $object10->setObjOmschrijving("Nam accumsan placerat purus vehicula vulputate. Sed at odio tempor, suscipit ligula vel, pellentesque augue. Nulla cursus erat non enim varius vehicula. Suspendisse potenti. Aliquam viverra ex ex, in cursus odio euismod vel. Sed in purus at nunc viverra fringilla eget nec lacus. Donec mollis libero aliquam risus ultricies, in euismod odio volutpat. Suspendisse bibendum arcu eros, eget placerat nunc sodales eu.");
        $object10->setChassisnummer("7BMDFH23904737121");
        $object10->setFotos(array('0e5769552be7f881cced0cf89a906154.png'));
        $object10->setKenteken("DD-419-L");
        $object10->setObjNaam("Optima T70E");
        $object10->setObjType("Camper");
        $object10->setPrijs(115);

        $optie10 = new OptieProduct();
        $optie10->setOptieTitel('Verzekerng hagelschade');
        $optie10->setOptieOmschrijving('Een top verzekering hagelschade');
        $optie10->setOptiePrijs('50');

        $specificatie10 = new Specificatie();
        $specificatie10->setMerk("Optima");
        $specificatie10->setType("T70E");
        $specificatie10->setBouwjaar(2018);
        $specificatie10->setMassaInventaris(2900);
        $specificatie10->setMassaMax(1350);
        $specificatie10->setLengteTot(738);
        $specificatie10->setLengteOpbouw(510);
        $specificatie10->setHoogte(270);
        $specificatie10->setRijbewijsBenodigd("B");

        $object10->setSpecificatie($specificatie10);

        //row 11
        $object11 = new ObjectProduct();
        $object11->setBeschikbaarheid(true);
        $object11->setObjOmschrijving("Aliquam vel sodales orci. Nam nec blandit ipsum. Morbi nulla enim, pulvinar vel sodales sit amet, condimentum sed nulla. Donec eleifend arcu id enim gravida ultricies. Sed ac posuere tortor. Duis tempor ante eu elit imperdiet, a volutpat sem ultricies. Quisque quis elementum ex. Donec non consequat nibh. Aliquam cursus euismod ipsum. Aliquam erat volutpat. Donec ultricies a libero ut sollicitudin. Vivamus dictum porttitor neque, non congue ex imperdiet et. Nam nec lectus quis urna egestas ultricies non sed nisi. Nam vulputate tortor risus, ut malesuada odio tempus sed. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Mauris lorem tellus, luctus vitae ornare vel, tempor at eros.");
        $object11->setChassisnummer("7BMDK239067822023");
        $object11->setFotos(array('0e5769552be7f881cced0cf89a906154.png'));
        $object11->setKenteken("DZ-712-R");
        $object11->setObjNaam("Optima A65GM");
        $object11->setObjType("Camper");
        $object11->setPrijs(115);

        $specificatie11 = new Specificatie();
        $specificatie11->setMerk("Optima");
        $specificatie11->setType("A65GM");
        $specificatie11->setBouwjaar(2016);
        $specificatie11->setMassaInventaris(3000);
        $specificatie11->setMassaMax(3650);
        $specificatie11->setLengteTot(649);
        $specificatie11->setLengteOpbouw(520);
        $specificatie11->setHoogte(277);
        $specificatie11->setRijbewijsBenodigd("CE");

        $object11->setSpecificatie($specificatie11);

        // order 1
        $order1 = new KlantOrder();
        $order1->setOrdernummer(random_int(1, 800000000));
        $order1->setOrderDatum(new \DateTime('now - 1 year'));
        $order1->setKlant($klant);
        $objectPeriod1 = new ObjectProductPeriod();
        $objectPeriod1->setDatumUit(new \DateTime('now - 1 year'));
        $objectPeriod1->setDatumTerug(new \DateTime('now - 1 year'));
        $objectPeriod1->setObjectProduct($object1);
        $objectPeriod1->setKlantOrder($order1);
        $optionPeriod1 = new OptionProductPeriod();
        $optionPeriod1->setDatumUit(new \DateTime('now - 1 year'));
        $optionPeriod1->setDatumTerug(new \DateTime('now - 1 year'));
        $optionPeriod1->setOptionProduct($optie1);
        $optionPeriod1->setKlantOrder($order1);
        $order1->setObjectPeriod($objectPeriod1);
        $order1->addOptionPeriod($optionPeriod1);

        // order 2
        $order2 = new KlantOrder();
        $order2->setOrdernummer(random_int(1, 800000000));
        $order2->setOrderDatum(new \DateTime('now - 1 year'));
        $order2->setKlant($klant);
        $objectPeriod2 = new ObjectProductPeriod();
        $objectPeriod2->setDatumUit(new \DateTime('now - 1 year'));
        $objectPeriod2->setDatumTerug(new \DateTime('now - 1 year'));
        $objectPeriod2->setObjectProduct($object2);
        $objectPeriod2->setKlantOrder($order2);
        $optionPeriod2 = new OptionProductPeriod();
        $optionPeriod2->setDatumUit(new \DateTime('now - 1 year'));
        $optionPeriod2->setDatumTerug(new \DateTime('now - 1 year'));
        $optionPeriod2->setOptionProduct($optie2);
        $optionPeriod2->setKlantOrder($order2);
        $order2->setObjectPeriod($objectPeriod2);
        $order2->addOptionPeriod($optionPeriod2);

        // order 3
        $order3 = new KlantOrder();
        $order3->setOrdernummer(random_int(1, 800000000));
        $order3->setOrderDatum(new \DateTime('now - 1 year'));
        $order3->setKlant($klant);
        $objectPeriod3 = new ObjectProductPeriod();
        $objectPeriod3->setDatumUit(new \DateTime('now - 1 year'));
        $objectPeriod3->setDatumTerug(new \DateTime('now - 1 year'));
        $objectPeriod3->setObjectProduct($object3);
        $objectPeriod3->setKlantOrder($order3);
        $optionPeriod3 = new OptionProductPeriod();
        $optionPeriod3->setDatumUit(new \DateTime('now - 1 year'));
        $optionPeriod3->setDatumTerug(new \DateTime('now - 1 year'));
        $optionPeriod3->setOptionProduct($optie3);
        $optionPeriod3->setKlantOrder($order3);
        $order3->setObjectPeriod($objectPeriod3);
        $order3->addOptionPeriod($optionPeriod3);

        // order 4
        $order4 = new KlantOrder();
        $order4->setOrdernummer(random_int(1, 800000000));
        $order4->setOrderDatum(new \DateTime('now - 1 year'));
        $order4->setKlant($klant);
        $objectPeriod4 = new ObjectProductPeriod();
        $objectPeriod4->setDatumUit(new \DateTime('now - 1 year'));
        $objectPeriod4->setDatumTerug(new \DateTime('now - 1 year'));
        $objectPeriod4->setObjectProduct($object4);
        $objectPeriod4->setKlantOrder($order4);
        $optionPeriod4 = new OptionProductPeriod();
        $optionPeriod4->setDatumUit(new \DateTime('now - 1 year'));
        $optionPeriod4->setDatumTerug(new \DateTime('now - 1 year'));
        $optionPeriod4->setOptionProduct($optie4);
        $optionPeriod4->setKlantOrder($order4);
        $order4->setObjectPeriod($objectPeriod4);
        $order4->addOptionPeriod($optionPeriod4);

        // order 5
        $order5 = new KlantOrder();
        $order5->setOrdernummer(random_int(1, 800000000));
        $order5->setOrderDatum(new \DateTime('now - 1 year'));
        $order5->setKlant($klant);
        $objectPeriod5 = new ObjectProductPeriod();
        $objectPeriod5->setDatumUit(new \DateTime('now - 1 year'));
        $objectPeriod5->setDatumTerug(new \DateTime('now - 1 year'));
        $objectPeriod5->setObjectProduct($object5);
        $objectPeriod5->setKlantOrder($order5);
        $optionPeriod5 = new OptionProductPeriod();
        $optionPeriod5->setDatumUit(new \DateTime('now - 1 year'));
        $optionPeriod5->setDatumTerug(new \DateTime('now - 1 year'));
        $optionPeriod5->setOptionProduct($optie5);
        $optionPeriod5->setKlantOrder($order5);
        $order5->setObjectPeriod($objectPeriod5);
        $order5->addOptionPeriod($optionPeriod5);

        // order 6
        $order6 = new KlantOrder();
        $order6->setOrdernummer(random_int(1, 800000000));
        $order6->setOrderDatum(new \DateTime('now - 1 year'));
        $order6->setKlant($klant);
        $objectPeriod6 = new ObjectProductPeriod();
        $objectPeriod6->setDatumUit(new \DateTime('now - 1 year'));
        $objectPeriod6->setDatumTerug(new \DateTime('now - 1 year'));
        $objectPeriod6->setObjectProduct($object6);
        $objectPeriod6->setKlantOrder($order6);
        $optionPeriod6 = new OptionProductPeriod();
        $optionPeriod6->setDatumUit(new \DateTime('now - 1 year'));
        $optionPeriod6->setDatumTerug(new \DateTime('now - 1 year'));
        $optionPeriod6->setOptionProduct($optie6);
        $optionPeriod6->setKlantOrder($order6);
        $order6->setObjectPeriod($objectPeriod6);
        $order6->addOptionPeriod($optionPeriod6);

        // order 7
        $order7 = new KlantOrder();
        $order7->setOrdernummer(random_int(1, 800000000));
        $order7->setOrderDatum(new \DateTime('now - 1 year'));
        $order7->setKlant($klant);
        $objectPeriod7 = new ObjectProductPeriod();
        $objectPeriod7->setDatumUit(new \DateTime('now - 1 year'));
        $objectPeriod7->setDatumTerug(new \DateTime('now - 1 year'));
        $objectPeriod7->setObjectProduct($object7);
        $objectPeriod7->setKlantOrder($order7);
        $optionPeriod7 = new OptionProductPeriod();
        $optionPeriod7->setDatumUit(new \DateTime('now - 1 year'));
        $optionPeriod7->setDatumTerug(new \DateTime('now - 1 year'));
        $optionPeriod7->setOptionProduct($optie7);
        $optionPeriod7->setKlantOrder($order7);
        $order7->setObjectPeriod($objectPeriod7);
        $order7->addOptionPeriod($optionPeriod7);

        // order 8
        $order8 = new KlantOrder();
        $order8->setOrdernummer(random_int(1, 800000000));
        $order8->setOrderDatum(new \DateTime('now - 1 year'));
        $order8->setKlant($klant);
        $objectPeriod8 = new ObjectProductPeriod();
        $objectPeriod8->setDatumUit(new \DateTime('now - 1 year'));
        $objectPeriod8->setDatumTerug(new \DateTime('now - 1 year'));
        $objectPeriod8->setObjectProduct($object8);
        $objectPeriod8->setKlantOrder($order8);
        $optionPeriod8 = new OptionProductPeriod();
        $optionPeriod8->setDatumUit(new \DateTime('now - 1 year'));
        $optionPeriod8->setDatumTerug(new \DateTime('now - 1 year'));
        $optionPeriod8->setOptionProduct($optie8);
        $optionPeriod8->setKlantOrder($order8);
        $order8->setObjectPeriod($objectPeriod8);
        $order8->addOptionPeriod($optionPeriod8);

        // order 9
        $order9 = new KlantOrder();
        $order9->setOrdernummer(random_int(1, 800000000));
        $order9->setOrderDatum(new \DateTime('now - 1 year'));
        $order9->setKlant($klant);
        $objectPeriod9 = new ObjectProductPeriod();
        $objectPeriod9->setDatumUit(new \DateTime('now - 1 year'));
        $objectPeriod9->setDatumTerug(new \DateTime('now - 1 year'));
        $objectPeriod9->setObjectProduct($object9);
        $objectPeriod9->setKlantOrder($order9);
        $optionPeriod9 = new OptionProductPeriod();
        $optionPeriod9->setDatumUit(new \DateTime('now - 1 year'));
        $optionPeriod9->setDatumTerug(new \DateTime('now - 1 year'));
        $optionPeriod9->setOptionProduct($optie9);
        $optionPeriod9->setKlantOrder($order9);
        $order9->setObjectPeriod($objectPeriod9);
        $order9->addOptionPeriod($optionPeriod9);

        // order 10
        $order10 = new KlantOrder();
        $order10->setOrdernummer(random_int(1, 800000000));
        $order10->setOrderDatum(new \DateTime('now - 1 year'));
        $order10->setKlant($klant);
        $objectPeriod10 = new ObjectProductPeriod();
        $objectPeriod10->setDatumUit(new \DateTime('now - 1 year'));
        $objectPeriod10->setDatumTerug(new \DateTime('now - 1 year'));
        $objectPeriod10->setObjectProduct($object10);
        $objectPeriod10->setKlantOrder($order10);
        $optionPeriod10 = new OptionProductPeriod();
        $optionPeriod10->setDatumUit(new \DateTime('now - 1 year'));
        $optionPeriod10->setDatumTerug(new \DateTime('now - 1 year'));
        $optionPeriod10->setOptionProduct($optie10);
        $optionPeriod10->setKlantOrder($order10);
        $order10->setObjectPeriod($objectPeriod10);
        $order10->addOptionPeriod($optionPeriod10);

        // order 11
        $order11 = new KlantOrder();
        $order11->setOrdernummer(random_int(1, 800000000));
        $order11->setOrderDatum(new \DateTime('now - 1 year'));
        $order11->setKlant($klant);
        $objectPeriod11 = new ObjectProductPeriod();
        $objectPeriod11->setDatumUit(new \DateTime('now - 1 year'));
        $objectPeriod11->setDatumTerug(new \DateTime('now - 1 year'));
        $objectPeriod11->setObjectProduct($object11);
        $objectPeriod11->setKlantOrder($order11);
        $order11->setObjectPeriod($objectPeriod11);

//        $beheerder = new Klantaccount();
//        $beheerder->setUsername('beheerder');
//        $beheer_pw = $passwordEncoder->encodePassword($beheerder, 'lollol');
//        $beheerder->setPassword($beheer_pw);
//        $beheerder->setEmail('beheerder@dedissel.nl');
//        $beheerder->setIsVerified(true);
//        $beheerder->setIsActive(true);


        $em = $this->getDoctrine()->getManager();
        $em->persist($klant);
//        $em->persist($beheerder);
        $em->persist($order1);
        $em->persist($order2);
        $em->persist($order3);
        $em->persist($order4);
        $em->persist($order5);
        $em->persist($order6);
        $em->persist($order7);
        $em->persist($order8);
        $em->persist($order9);
        $em->persist($order10);
        $em->persist($order11);
        $em->flush();
        return $this->redirectToRoute('homepage');
    }
}
