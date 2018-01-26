<?php
/**
 * Created by PhpStorm.
 * User: imoz
 * Date: 25/01/2018
 * Time: 21:20
 */

namespace App\Form;


use App\Entity\Klantaccount;
use GuzzleHttp\Client;
use function Sodium\add;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @property Client guzzle
 */
class CaptchaType extends AbstractType
{
    public function __construct(Client $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('g-recaptcha-response', TextareaType::class, array(
            'constraints' => array(
                new Callback(array(
                    'callback' => function ($data, ExecutionContextInterface $context){
                        $body = $this->guzzle
                            ->request('POST', 'https://www.google.com/recaptcha/api/siteverify', array(
                                'query'=> array(
                                    'secret' => '6LcKDkIUAAAAAPDubNU9npfojKhxnPFrnB4ki-oU',
                                    'response' => $data
                                )
                            ));
                        $response = json_decode($body->getBody()->getContents(), true);

                        if(!$response['success']){
                            $context
                                ->buildViolation('Verificatie mislukt!')
                                ->addViolation();
                        }
                    }
                ))
            ),
            'required' => true
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }
    public function getBlockPrefix() {
        return null;
    }
}