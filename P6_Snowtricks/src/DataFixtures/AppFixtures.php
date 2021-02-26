<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Trick;
use App\Entity\Picture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
            $this->encoder=$encoder;
    }

    /*const FAKE_DATA = [
        0 => [
            'name' => 'nama1',
            'description' => 'blabla',
            'creationDate' => '',
            'mainPicture' => '',
            'category' => [
                'cat1',
                'cat2'
            ],
            'comment' => [
                'com1',
                'com2'
            ],
            'user' => [
                'name' => '',
                'email' => '',
            ] ,
            'picture' => '',
            'video' => [
                'url' => ''
            ],
        ]
    ];*/


    public function load(ObjectManager $manager)
    {
        //foreach(self::FAKE_DATA as $key => $value) {

        //user
        /*if ($value === 'user'){
            $user = new User();

            $user->setFirstName($value['firstname'])
                ->setLastName($value['lastname'])
                ->setUsername($value['username'])
                ->setEmail($value['email'])
                ->setPassword($value['password'])
                ->setvalidated($value['isValidated']);
        }

        //$manager->persist($user[$i]);
    }*/

        //Category fixtures
        $category1= new Category();
        $category1  ->setName('Straight airs');
        $manager->persist($category1);

        $category2= new Category();
        $category2  ->setName('Grabs');
        $manager->persist($category2);

        $category3= new Category();
        $category3  ->setName('Spins');
        $manager->persist($category3);

        $category4= new Category();
        $category4  ->setName('Inverted hand plants');
        $manager->persist($category4);

        $category5= new Category();
        $category5  ->setName('Slides');
        $manager->persist($category5);

        //User fixtures

        $user1 = new User();
        $hash=$this->encoder->encodePassword($user1, 'password');
        $user1   ->setFirstName('jake')
                ->setLastName('mikkelson')
                ->setUsername('jackie90566')
                ->setEmail('j.mick@mail.fr')
                ->setPassword($hash)
                ->setvalidated('true');

        //Trick fixtures
        $trick1 = new Trick();
        $trick1->setName('Ollie')
            ->setDescription('A trick in which the snowboarder springs off the tail of the board and into the air.')
            ->setCreationDate('2020-02-10')
            ->setmodificationDate('2020-03-10')
            ->setContent('Inter quos Paulus eminebat notarius ortus in Hispania, glabro quidam sub vultu latens, odorandi vias periculorum occultas perquam sagax. is in Brittanniam missus ut militares quosdam perduceret ausos conspirasse Magnentio, cum reniti non possent, iussa licentius supergressus fluminis modo fortunis conplurium sese repentinus infudit et ferebatur per strages multiplices ac ruinas, vinculis membra ingenuorum adfligens et quosdam obterens manicis, crimina scilicet multa consarcinando a veritate longe discreta. unde admissum est facinus impium, quod Constanti tempus nota inusserat sempiterna.
Isdem diebus Apollinaris Domitiani gener, paulo ante agens palatii Caesaris curam, ad Mesopotamiam missus a socero per militares numeros immodice scrutabatur, an quaedam altiora meditantis iam Galli secreta susceperint scripta, qui conpertis Antiochiae gestis per minorem Armeniam lapsus Constantinopolim petit exindeque per protectores retractus artissime tenebatur.')
            ->setUsername($user1)
            ->setCategory($category5)
            ->setMainPicture('2b5accdcdb62f6272fa7653c8f5e579e.png');

        $manager->persist($trick1);

        //Picture fixtures
        $picture1=new Picture();
        $picture1->setTitle('Ollie style')
            ->setFileName('Ollieemdhcbsnjsocncpjcpdsn.png')
            ->setTricks($trick1);
        $manager->persist($picture1);

        $manager->flush();
    }
}