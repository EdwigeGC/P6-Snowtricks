<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Picture;
use App\Entity\Trick;
use App\Entity\Video;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        //Category fixtures
        $category1 = new Category();
        $category1->setName('Straight airs');
        $manager->persist($category1);

        $category2 = new Category();
        $category2->setName('Grabs');
        $manager->persist($category2);

        $category3 = new Category();
        $category3->setName('Flips and inverted rotations');
        $manager->persist($category3);

        $category4 = new Category();
        $category4->setName('Inverted hand plants');
        $manager->persist($category4);

        $category5 = new Category();
        $category5->setName('Slides');
        $manager->persist($category5);

        //User fixtures
        $user1 = new User();
        $hash = $this->encoder->encodePassword($user1, 'pass1');
        $user1->setFirstName('jake')
                ->setLastName('mikkelson')
                ->setUsername('jake90566')
                ->setEmail('j.mikk@mail.com')
                ->setPassword($hash)
                ->setphoto('photoUser1.png')
                ->setvalidated('true');
        $manager->persist($user1);

        $user2 = new User();
        $hash = $this->encoder->encodePassword($user2, 'pass2');
        $user2->setFirstName('simone')
            ->setLastName('marteen')
            ->setUsername('simone 66')
            ->setEmail('s.mart@mail.com')
            ->setPassword($hash)
            ->setphoto('photoUser2.png')
            ->setvalidated('true');
        $manager->persist($user2);

        $user3 = new User();
        $hash = $this->encoder->encodePassword($user3, 'pass3');
        $user3->setFirstName('olivia')
            ->setLastName('pope')
            ->setUsername('oliv78')
            ->setEmail('à.pope@mail.com')
            ->setPassword($hash)
            ->setphoto('photoUser3.png')
            ->setvalidated('true');
        $manager->persist($user3);

        //Trick fixtures
        $trick1 = new Trick();
        $trick1->setName('Ollie')
            ->setDescription('A trick in which the snowboarder springs off the tail of the board and into the air. Inter quos Paulus eminebat notarius ortus in Hispania, glabro quidam sub vultu latens, odorandi vias periculorum occultas perquam sagax. is in Brittanniam missus ut militares quosdam perduceret ausos conspirasse Magnentio, cum reniti non possent, iussa licentius supergressus fluminis modo fortunis conplurium sese repentinus infudit et ferebatur per strages multiplices ac ruinas, vinculis membra ingenuorum adfligens et quosdam obterens manicis, crimina scilicet multa consarcinando a veritate longe discreta. unde admissum est facinus impium, quod Constanti tempus nota inusserat sempiterna.
Isdem diebus Apollinaris Domitiani gener, paulo ante agens palatii Caesaris curam, ad Mesopotamiam missus a socero per militares numeros immodice scrutabatur, an quaedam altiora meditantis iam Galli secreta susceperint scripta, qui conpertis Antiochiae gestis per minorem Armeniam lapsus Constantinopolim petit exindeque per protectores retractus artissime tenebatur.')
            ->setCreationDate(new \DateTime('2021-02-13 18:23:12'))
            ->setModificationDate(new \DateTime('2021-03-10 09:35:34'))
            ->setMainPicture('Ollie.png')
            ->setCategory($category1)
            ->setUser($user1);
        $manager->persist($trick1);

        $trick2 = new Trick();
        $trick2->setName('Air-to-fakie')
            ->setDescription('Airing straight out of a vertical transition (halfpipe, quarterpipe) and then re-entering fakie, without rotation.
 Inter quos Paulus eminebat notarius ortus in Hispania, glabro quidam sub vultu latens, odorandi vias periculorum occultas perquam sagax. is in Brittanniam missus ut militares quosdam perduceret ausos conspirasse Magnentio, cum reniti non possent, iussa licentius supergressus fluminis modo fortunis conplurium sese repentinus infudit et ferebatur per strages multiplices ac ruinas, vinculis membra ingenuorum adfligens et quosdam obterens manicis, crimina scilicet multa consarcinando a veritate longe discreta. unde admissum est facinus impium, quod Constanti tempus nota inusserat sempiterna.
Isdem diebus Apollinaris Domitiani gener, paulo ante agens palatii Caesaris curam, ad Mesopotamiam missus a socero per militares numeros immodice scrutabatur, an quaedam altiora meditantis iam Galli secreta susceperint scripta, qui conpertis Antiochiae gestis per minorem Armeniam lapsus Constantinopolim petit exindeque per protectores retractus artissime tenebatur.')
            ->setCreationDate(new \DateTime('2021-01-15 12:14:34'))
            ->setModificationDate(null)
            ->setMainPicture('airtofakie.png')
            ->setCategory($category1)
            ->setUser($user2);
        $manager->persist($trick2);

        $trick3 = new Trick();
        $trick3->setName('Poptart')
            ->setDescription('Airing from fakie to forward on a quarterpipe or halfpipe without rotation.
 Inter quos Paulus eminebat notarius ortus in Hispania, glabro quidam sub vultu latens, odorandi vias periculorum occultas perquam sagax. is in Brittanniam missus ut militares quosdam perduceret ausos conspirasse Magnentio, cum reniti non possent, iussa licentius supergressus fluminis modo fortunis conplurium sese repentinus infudit et ferebatur per strages multiplices ac ruinas, vinculis membra ingenuorum adfligens et quosdam obterens manicis, crimina scilicet multa consarcinando a veritate longe discreta. unde admissum est facinus impium, quod Constanti tempus nota inusserat sempiterna.
Isdem diebus Apollinaris Domitiani gener, paulo ante agens palatii Caesaris curam, ad Mesopotamiam missus a socero per militares numeros immodice scrutabatur, an quaedam altiora meditantis iam Galli secreta susceperint scripta, qui conpertis Antiochiae gestis per minorem Armeniam lapsus Constantinopolim petit exindeque per protectores retractus artissime tenebatur.')
            ->setCreationDate(new \DateTime('2021-02-01 23:17:09'))
            ->setModificationDate(new \DateTime('2021-02-05 12:14:34'))
            ->setMainPicture('Poptart.png')
            ->setCategory($category1)
            ->setUser($user2);
        $manager->persist($trick3);

        $trick4 = new Trick();
        $trick4->setName('One-Two')
            ->setDescription('A trick in which the rider\'s front hand grabs the heel edge behind their back foot.
 Inter quos Paulus eminebat notarius ortus in Hispania, glabro quidam sub vultu latens, odorandi vias periculorum occultas perquam sagax. is in Brittanniam missus ut militares quosdam perduceret ausos conspirasse Magnentio, cum reniti non possent, iussa licentius supergressus fluminis modo fortunis conplurium sese repentinus infudit et ferebatur per strages multiplices ac ruinas, vinculis membra ingenuorum adfligens et quosdam obterens manicis, crimina scilicet multa consarcinando a veritate longe discreta. unde admissum est facinus impium, quod Constanti tempus nota inusserat sempiterna.
Isdem diebus Apollinaris Domitiani gener, paulo ante agens palatii Caesaris curam, ad Mesopotamiam missus a socero per militares numeros immodice scrutabatur, an quaedam altiora meditantis iam Galli secreta susceperint scripta, qui conpertis Antiochiae gestis per minorem Armeniam lapsus Constantinopolim petit exindeque per protectores retractus artissime tenebatur.')
            ->setCreationDate(new \DateTime('2021-01-20 19:12:34'))
            ->setModificationDate(null)
            ->setMainPicture('One-Two.png')
            ->setCategory($category2)
            ->setUser($user3);
        $manager->persist($trick4);

        $trick5 = new Trick();
        $trick5->setName('Chicken salad')
            ->setDescription('The rear hand reaches between the legs and grabs the heel edge between the bindings while the front leg is boned. The wrist is rotated inward to complete the grab.
 Inter quos Paulus eminebat notarius ortus in Hispania, glabro quidam sub vultu latens, odorandi vias periculorum occultas perquam sagax. is in Brittanniam missus ut militares quosdam perduceret ausos conspirasse Magnentio, cum reniti non possent, iussa licentius supergressus fluminis modo fortunis conplurium sese repentinus infudit et ferebatur per strages multiplices ac ruinas, vinculis membra ingenuorum adfligens et quosdam obterens manicis, crimina scilicet multa consarcinando a veritate longe discreta. unde admissum est facinus impium, quod Constanti tempus nota inusserat sempiterna.
Isdem diebus Apollinaris Domitiani gener, paulo ante agens palatii Caesaris curam, ad Mesopotamiam missus a socero per militares numeros immodice scrutabatur, an quaedam altiora meditantis iam Galli secreta susceperint scripta, qui conpertis Antiochiae gestis per minorem Armeniam lapsus Constantinopolim petit exindeque per protectores retractus artissime tenebatur.')
            ->setCreationDate(new \DateTime('2021-02-20 12:14:34'))
            ->setModificationDate(new \DateTime('2021-03-01 16:15:48'))
            ->setMainPicture('chickensalad.png')
            ->setCategory($category2)
            ->setUser($user1);
        $manager->persist($trick5);

        $trick6 = new Trick();
        $trick6->setName('Japan Air')
            ->setDescription('The front hand grabs the toe edge in between the feet and the front knee is pulled to the board.
 Inter quos Paulus eminebat notarius ortus in Hispania, glabro quidam sub vultu latens, odorandi vias periculorum occultas perquam sagax. is in Brittanniam missus ut militares quosdam perduceret ausos conspirasse Magnentio, cum reniti non possent, iussa licentius supergressus fluminis modo fortunis conplurium sese repentinus infudit et ferebatur per strages multiplices ac ruinas, vinculis membra ingenuorum adfligens et quosdam obterens manicis, crimina scilicet multa consarcinando a veritate longe discreta. unde admissum est facinus impium, quod Constanti tempus nota inusserat sempiterna.
Isdem diebus Apollinaris Domitiani gener, paulo ante agens palatii Caesaris curam, ad Mesopotamiam missus a socero per militares numeros immodice scrutabatur, an quaedam altiora meditantis iam Galli secreta susceperint scripta, qui conpertis Antiochiae gestis per minorem Armeniam lapsus Constantinopolim petit exindeque per protectores retractus artissime tenebatur.')
            ->setCreationDate(new \DateTime('2021-02-04 15:47:18'))
            ->setModificationDate(new \DateTime('2021-03-01 12:14:34'))
            ->setMainPicture('japanair.png')
            ->setCategory($category2)
            ->setUser($user3);
        $manager->persist($trick6);

        $trick7 = new Trick();
        $trick7->setName('50-50')
            ->setDescription('A slide in which a snowboarder rides straight along a rail or other obstacle. This trick has its origin in skateboarding, where the trick is performed with both skateboard trucks grinding along a rail.
 Inter quos Paulus eminebat notarius ortus in Hispania, glabro quidam sub vultu latens, odorandi vias periculorum occultas perquam sagax. is in Brittanniam missus ut militares quosdam perduceret ausos conspirasse Magnentio, cum reniti non possent, iussa licentius supergressus fluminis modo fortunis conplurium sese repentinus infudit et ferebatur per strages multiplices ac ruinas, vinculis membra ingenuorum adfligens et quosdam obterens manicis, crimina scilicet multa consarcinando a veritate longe discreta. unde admissum est facinus impium, quod Constanti tempus nota inusserat sempiterna.
Isdem diebus Apollinaris Domitiani gener, paulo ante agens palatii Caesaris curam, ad Mesopotamiam missus a socero per militares numeros immodice scrutabatur, an quaedam altiora meditantis iam Galli secreta susceperint scripta, qui conpertis Antiochiae gestis per minorem Armeniam lapsus Constantinopolim petit exindeque per protectores retractus artissime tenebatur.')
            ->setCreationDate(new \DateTime('2021-01-30 13:23:34'))
            ->setModificationDate(null)
            ->setMainPicture('5050tricks.png')
            ->setCategory($category5)
            ->setUser($user1);
        $manager->persist($trick7);

        $trick8 = new Trick();
        $trick8->setName('Boardslide')
            ->setDescription('A slide performed where the riders leading foot passes over the rail on approach, with their snowboard traveling perpendicular along the rail or other obstacle.[1] When performing a frontside boardslide, the snowboarder is facing uphill. When performing a backside boardslide, a snowboarder is facing downhill. This is often confusing to new riders learning the trick because with a frontside boardslide you are moving backward and with a backside boardslide you are moving forward.
 Inter quos Paulus eminebat notarius ortus in Hispania, glabro quidam sub vultu latens, odorandi vias periculorum occultas perquam sagax. is in Brittanniam missus ut militares quosdam perduceret ausos conspirasse Magnentio, cum reniti non possent, iussa licentius supergressus fluminis modo fortunis conplurium sese repentinus infudit et ferebatur per strages multiplices ac ruinas, vinculis membra ingenuorum adfligens et quosdam obterens manicis, crimina scilicet multa consarcinando a veritate longe discreta. unde admissum est facinus impium, quod Constanti tempus nota inusserat sempiterna.
Isdem diebus Apollinaris Domitiani gener, paulo ante agens palatii Caesaris curam, ad Mesopotamiam missus a socero per militares numeros immodice scrutabatur, an quaedam altiora meditantis iam Galli secreta susceperint scripta, qui conpertis Antiochiae gestis per minorem Armeniam lapsus Constantinopolim petit exindeque per protectores retractus artissime tenebatur.')
            ->setCreationDate(new \DateTime('2021-01-12 09:29:25'))
            ->setModificationDate(null)
            ->setMainPicture('boardSlide.png')
            ->setCategory($category5)
            ->setUser($user2);
        $manager->persist($trick8);

        $trick9 = new Trick();
        $trick9->setName('Backflip')
            ->setDescription('Flipping backwards (like a standing backflip) off of a jump.
 Inter quos Paulus eminebat notarius ortus in Hispania, glabro quidam sub vultu latens, odorandi vias periculorum occultas perquam sagax. is in Brittanniam missus ut militares quosdam perduceret ausos conspirasse Magnentio, cum reniti non possent, iussa licentius supergressus fluminis modo fortunis conplurium sese repentinus infudit et ferebatur per strages multiplices ac ruinas, vinculis membra ingenuorum adfligens et quosdam obterens manicis, crimina scilicet multa consarcinando a veritate longe discreta. unde admissum est facinus impium, quod Constanti tempus nota inusserat sempiterna.
Isdem diebus Apollinaris Domitiani gener, paulo ante agens palatii Caesaris curam, ad Mesopotamiam missus a socero per militares numeros immodice scrutabatur, an quaedam altiora meditantis iam Galli secreta susceperint scripta, qui conpertis Antiochiae gestis per minorem Armeniam lapsus Constantinopolim petit exindeque per protectores retractus artissime tenebatur.')
            ->setCreationDate(new \DateTime('2021-03-01 10:29:25'))
            ->setModificationDate(null)
            ->setMainPicture('backflip.png')
            ->setCategory($category3)
            ->setUser($user1);
        $manager->persist($trick9);

        $trick10 = new Trick();
        $trick10->setName('Wildcat')
            ->setDescription('A backflip performed on a straight jump, with an axis of rotation in which the snowboarder flips in a backward, cartwheel-like fashion. A double wildcat is called a supercat.
 Inter quos Paulus eminebat notarius ortus in Hispania, glabro quidam sub vultu latens, odorandi vias periculorum occultas perquam sagax. is in Brittanniam missus ut militares quosdam perduceret ausos conspirasse Magnentio, cum reniti non possent, iussa licentius supergressus fluminis modo fortunis conplurium sese repentinus infudit et ferebatur per strages multiplices ac ruinas, vinculis membra ingenuorum adfligens et quosdam obterens manicis, crimina scilicet multa consarcinando a veritate longe discreta. unde admissum est facinus impium, quod Constanti tempus nota inusserat sempiterna.
Isdem diebus Apollinaris Domitiani gener, paulo ante agens palatii Caesaris curam, ad Mesopotamiam missus a socero per militares numeros immodice scrutabatur, an quaedam altiora meditantis iam Galli secreta susceperint scripta, qui conpertis Antiochiae gestis per minorem Armeniam lapsus Constantinopolim petit exindeque per protectores retractus artissime tenebatur.')
            ->setCreationDate(new \DateTime('2021-01-08 10:29:25'))
            ->setModificationDate(new \DateTime('2021-02-05 15:44:12'))
            ->setMainPicture('wildcat.png')
            ->setCategory($category3)
            ->setUser($user2);
        $manager->persist($trick10);

        $trick11 = new Trick();
        $trick11->setName('The Gutterball')
            ->setDescription('The Gutterball is a one footed (front foot is strapped in and the rear foot is unstrapped ) front boardslide with a backhanded seatbelt nose grab, resembling the body position that someone would have after releasing a bowling ball down a bowling ally. This trick was invented and named by Jeremy Cameron which won him a first place in the Morrow Snowboards "FAME WAR" Best Trick contest in 2009.
 Inter quos Paulus eminebat notarius ortus in Hispania, glabro quidam sub vultu latens, odorandi vias periculorum occultas perquam sagax. is in Brittanniam missus ut militares quosdam perduceret ausos conspirasse Magnentio, cum reniti non possent, iussa licentius supergressus fluminis modo fortunis conplurium sese repentinus infudit et ferebatur per strages multiplices ac ruinas, vinculis membra ingenuorum adfligens et quosdam obterens manicis, crimina scilicet multa consarcinando a veritate longe discreta. unde admissum est facinus impium, quod Constanti tempus nota inusserat sempiterna.
Isdem diebus Apollinaris Domitiani gener, paulo ante agens palatii Caesaris curam, ad Mesopotamiam missus a socero per militares numeros immodice scrutabatur, an quaedam altiora meditantis iam Galli secreta susceperint scripta, qui conpertis Antiochiae gestis per minorem Armeniam lapsus Constantinopolim petit exindeque per protectores retractus artissime tenebatur.')
            ->setCreationDate(new \DateTime('2021-01-12 09:29:25'))
            ->setModificationDate(null)
            ->setMainPicture('gutterball.png')
            ->setCategory($category5)
            ->setUser($user3);
        $manager->persist($trick11);

        $trick12 = new Trick();
        $trick12->setName('Cork')
            ->setDescription('Spins are corked or corkscrew when the axis of the spin allows for the snowboarder to be oriented sideways or upside-down in the air, typically without becoming completely inverted (though the head and shoulders should drop below the relative position of the board). A Double-Cork refers to a rotation in which a snowboarder inverts or orients themselves sideways at two distinct times during an aerial rotation. David Benedek is the originator of the Double-Cork in the Half-pipe, but the Double-Cork is also a very common trick in Big-Air competitions. Shaun White is known for making this trick famous in the half-pipe. Several snowboarders have recently extended the limits of technical snowboarding by performing triple-cork variations, Torstein Horgmo being the first to land one in competition. Mark McMorris originated Backside Triple-Cork 1440\'s in 2011. In April 2015 British snowboarder and Winter Olympic medallist Billy Morgan demonstrated the world\'s first quadruple cork 1800.
 Inter quos Paulus eminebat notarius ortus in Hispania, glabro quidam sub vultu latens, odorandi vias periculorum occultas perquam sagax. is in Brittanniam missus ut militares quosdam perduceret ausos conspirasse Magnentio, cum reniti non possent, iussa licentius supergressus fluminis modo fortunis conplurium sese repentinus infudit et ferebatur per strages multiplices ac ruinas, vinculis membra ingenuorum adfligens et quosdam obterens manicis, crimina scilicet multa consarcinando a veritate longe discreta. unde admissum est facinus impium, quod Constanti tempus nota inusserat sempiterna.
Isdem diebus Apollinaris Domitiani gener, paulo ante agens palatii Caesaris curam, ad Mesopotamiam missus a socero per militares numeros immodice scrutabatur, an quaedam altiora meditantis iam Galli secreta susceperint scripta, qui conpertis Antiochiae gestis per minorem Armeniam lapsus Constantinopolim petit exindeque per protectores retractus artissime tenebatur.')
            ->setCreationDate(new \DateTime('2021-02-17 10:29:25'))
            ->setModificationDate(new \DateTime('2021-02-26 13:34:09'))
            ->setMainPicture('cork.png')
            ->setCategory($category5)
            ->setUser($user1);
        $manager->persist($trick12);

        //Array for random distribution
        $users = [$user1, $user2, $user3];
        $tricks = [$trick1, $trick2, $trick3, $trick4, $trick5, $trick6, $trick7, $trick8, $trick9, $trick10, $trick11, $trick12];
        $pictures = ['5050tricks.png', 'airtofakie.png', 'backflip.png', 'boardSlide.png', 'chickensalad.png', 'cork.png', 'gutterball.png', 'japanair.png', 'Ollie.png', 'One-Two.png', 'Poptart.png', 'wildcat.png'];
        $videos = ['https://www.youtube.com/embed/0uGETVnkujA', 'https://www.youtube.com/embed/7KUpodSrZqI', 'https://www.youtube.com/embed/X_WhGuIY9Ak', 'https://www.youtube.com/embed/zWxBgxq5rP0'];

        //Picture fixtures
        for ($i = 1; $i <= 30; ++$i) {
            $picture = new Picture();
            $picture->setTitle('Title picture')
                ->setFileName($pictures[array_rand($pictures, 1)])
                ->setTricks($tricks[array_rand($tricks, 1)]);
            $manager->persist($picture);
        }

        //Video fixtures
        for ($j = 1; $j <= 20; ++$j) {
            $video = new Video();
            $video->setTitle('title video')
                ->setUrl($videos[array_rand($videos, 1)])
                ->setTricks($tricks[array_rand($tricks, 1)]);
            $manager->persist($video);
        }

        //Comment fixtures
        for ($k = 1; $k <= 100; ++$k) {
            $comment = new Comment();

            $comment->setContent('great post')
                ->setUser($users[array_rand($users, 1)])
                ->setCreationDate(new \DateTime())
                ->setTricks($tricks[array_rand($tricks, 1)]);
            $manager->persist($comment);
        }

        $manager->flush();
    }
}
