<?php

namespace App\DataFixtures;

use App\Entity\Car;
use App\Entity\ContactMessage;
use App\Entity\Garage;
use App\Entity\ImageCar;
use App\Entity\ImageService;
use App\Entity\Schedule;
use App\Entity\Service;
use App\Entity\Testimonial;
use App\Entity\User;
use App\Entity\UserCustomer;
use App\Entity\UserStaffMember;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private Generator $faker;
    private array $brands;
    private array $engines;

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
        $this->faker = Factory::create('fr_FR');
        $this->brands = [
            "Peugeot" => ["208", "308", "3008"],
            "Renault" => ["Clio", "Megane", "Captur"],
            "Dacia" => ["Sandero Stepway", "Duster", "Logan"],
            "Citroën" => ["Picasso", "C3", "C4"],
            "Volkswagen" => ["Golf", "Polo", "Passat"]
        ];
        $this->engines = ['Essence', 'Diesel', 'Hybrid', 'Electrique'];
    }
    public function emptyDir($dossier): bool {
        if (!is_dir($dossier)) {
            return false;
        }
        $contenu = scandir($dossier);
        foreach ($contenu as $fichier) {
            if ($fichier != '.' && $fichier != '..') {
                $chemin = $dossier . '/' . $fichier;
                if (is_dir($chemin)) {
                    $this->rmdirRecursive($chemin);
                } else {
                    unlink($chemin);
                }
            }
        }
//        rmdir($dossier)
        return true;
    }


    public function load(ObjectManager $manager)
    {
        // Suppression du dossier uploads
        $this->emptyDir("/public/media/uploads");

        /**
         * Création d'un Schedule
         */
        $societyInfos = new Schedule();
        $societyInfos
            ->setOpenedDays([
                "1" => "Lun : 08h00 - 12h00, 13h00 - 17h00",
                "2" => "Mar : 08h00 - 12h00, 13h00 - 17h00",
                "3" => "Mer : 10h00 - 13h00, 14h00 - 18h00",
                "4" => "Jeu : 08h00 - 12h00, 13h00 - 17h00",
                "5" => "Ven : 08h00 - 12h00, 13h00 - 17h00",
                "6" => "Sam : 10h00 - 12h00, 13h00 - 16h00",
                "7" => "Dim : fermé"
            ])
        ;
        $manager->persist($societyInfos);

        /**
         * Creation d'un garage
         */
        $garage = new Garage();
        $garage
            ->setAddress('7 avenue du vase de Soissons, 31000 Toulouse')
            ->setEmail('vincentParrot@VP.com')
            ->setPhoneNumber('0123456789')
            ->setSchedule($societyInfos)
            ->setName('Siege Social')
        ;
        $manager->persist($garage);

        /**
         * Création des Cars
         * [
         * "Peugeot" => ["208", "308", "3008"],
         * "Renault" => ["Clio", "Megane", "Captur"],
         * "Dacia" => ["Sandero Stepway", "Duster", "Logan"],
         * "Citroën" => ["Picasso", "C3", "C4"],
         * "Volkswagen" => ["Golf", "Polo", "Passat"]
         * ];
         */
        $carEngines = ['Essence', 'Diesel', 'Electrique', 'Hybrid', 'Hydrogene'];
        foreach ($this->brands as $brand => $models) {
            foreach ($models as $model) {
                $car = new Car();
                $immatriculation = $this->faker->randomLetter().$this->faker->randomLetter().'-'.$this->faker->randomNumber(3,true).'-'.$this->faker->randomLetter().$this->faker->randomLetter();
                $car->setCarEngine($carEngines[array_rand($carEngines)])
                    ->setCarModel($model)
                    ->setCarConstructor($brand)
                    ->setGarage($garage)
                    ->setLicensePlate($immatriculation)
                    ->setMileage(mt_rand(800, 200000))
                    ->setPrice(mt_rand(12000, 50000))
                    ->setregistrationDate($this->faker->dateTimeBetween())
                    ->setPublished(true)
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setModifiedAt($car->getCreatedAt());

                /**
                 * Copie des photos de véhicule
                 */
                $photos = array_diff(scandir("src/DataFixtures/images/véhicules/{$brand}/{$model}"), ['.DS_Store', '.', '..']);
                foreach ($photos as $photo) {
                    $image = new ImageCar();
                    $image
                        ->setFilename($photo)
                        ->setAlt($photo);
                    $car->addImageCar($image);
                    copy("src/DataFixtures/images/véhicules/{$brand}/{$model}/{$photo}",
                    "public/media/uploads/{$image->getFilename()}");
                }

                $manager->persist($car);
            }
        }

        /**
         * Création de l'admin
         */
        $admin = new UserStaffMember();
        $admin
            ->setEmail("vincentParrot@VP.com")
            ->setRoles(["ROLE_SUPER_ADMIN"])
            ->setPassword($this->passwordHasher->hashPassword($admin,'%7913%!Ya!$cnS7s2'))
            ->setFirstname('Vincent')
            ->setLastName('Parrot')
            ->setGarage($garage)
        ;
        $manager->persist($admin);

        /**
         * Création d'un comtpe employé
         */
        $employee = new UserStaffMember();
        $employee
            ->setFirstname('John')
            ->setLastname('Doe')
            ->setGarage($garage)
            ->setEmail('johnDoe@VP.com')
            ->setPassword($this->passwordHasher->hashPassword($employee, 'MyFabulousPassword7!'))
            ->setRoles(["ROLE_ADMIN"])
        ;
        $manager->persist($employee);

        /**
         * Création d'un compte client
         */
        $customer = new UserCustomer();
        $customer
            ->setFirstname('Cloud')
            ->setLastname('Strife')
            ->setEmail('cloud.strife@gmail.com')
            ->setIsVerified(true)
            ->setPassword($this->passwordHasher->hashPassword($customer, 'MyFabulousPassword7!'))
            ->setRoles(["ROLE_CLIENT"])
        ;
        $manager->persist($customer);

        /** Service 1 */
        $image1 = new ImageService();
        $image1
            ->setAlt('Entretien et vidange')
            ->setFilename('Entretien et vidange.webp');
        $service1 = new Service();
        $service1
            ->setName('Entretien et vidange')
            ->setDescription(
                "Offrez à votre véhicule le soin qu'il mérite avec notre service d'entretien et de vidange de premier ordre. 
                Notre équipe d'experts qualifiés veillera à ce que votre voiture reste en parfait état de marche. 
                Nous utilisons les meilleures huiles et filtres pour garantir une performance optimale et une longévité accrue de votre moteur. 
                Ne négligez pas l'entretien de votre précieux véhicule, confiez-le à des professionnels qui en prendront soin comme s'il était le leur."
            )
            ->setPrice(80)
            ->setPublished(true)
            ->addImageService($image1);
        copy("src/DataFixtures/images/services/Entretien et vidange.webp", "public/media/uploads/{$image1->getFilename()}");
        $manager->persist($service1);

        /** Service 2 */
        $image2 = new ImageService();
        $image2
            ->setAlt('Révision')
            ->setFilename('Révision.webp');
        $service2 = new Service();
        $service2
            ->setName('Révision')
            ->setDescription("
            Votre sécurité sur la route est notre priorité numéro un, c'est pourquoi notre service de révision est conçu
             pour vous offrir une tranquillité d'esprit totale. Nos mécaniciens certifiés inspecteront minutieusement 
             chaque composant de votre véhicule, en effectuant les ajustements nécessaires et en remplaçant les pièces usées. 
             Que vous prévoyiez un long voyage ou simplement que vous souhaitiez rouler en toute confiance au quotidien, 
             notre service de révision vous assure que votre véhicule est en parfait état.
            ")
            ->setPrice(90)
            ->setPublished(true)
            ->addImageService($image2);
        copy("src/DataFixtures/images/services/Revision.webp", "public/media/uploads/{$image2->getFilename()}");
        $manager->persist($service2);

        /** Service 3 */
        $image3 = new ImageService();
        $image3
            ->setAlt('Courroie de distribution')
            ->setFilename('Courroie de distribution.webp');
        $service3 = new Service();
        $service3
            ->setName('Courroie de distribution')
            ->setDescription("
            La courroie de distribution est l'un des éléments les plus critiques de votre moteur, et son remplacement à 
            intervalles réguliers est essentiel pour éviter les pannes coûteuses. Laissez notre équipe de spécialistes 
            prendre en charge cette tâche délicate. Nous utilisons uniquement des pièces de qualité supérieure pour garantir 
            la fiabilité de votre véhicule. Avec notre service de changement de courroie de distribution, 
            vous pouvez conduire l'esprit tranquille, en sachant que votre moteur est entre de bonnes mains.
            ")
            ->setPrice(499)
            ->setPublished(true)
            ->addImageService($image3);
        copy("src/DataFixtures/images/services/Courroie.webp", "public/media/uploads/{$image3->getFilename()}");
        $manager->persist($service3);

        /** Service 4 */
        $image4 = new ImageService();
        $image4
            ->setAlt('Pneumatique')
            ->setFilename('Pneumatique.webp');
        $service4 = new Service();
        $service4
            ->setName('Pneumatiques')
            ->setDescription("
            Les pneus sont la seule liaison entre votre véhicule et la route. Assurez-vous d'avoir les pneus adaptés à 
            votre conduite et aux conditions routières. Chez nous, vous trouverez un large choix de pneumatiques de haute qualité, 
            adaptés à tous les budgets. Nous vous offrons également un service de montage professionnel pour vous garantir une adhérence optimale, 
            une tenue de route exceptionnelle et une durée de vie prolongée de vos pneus. Roulez en toute sécurité avec nos pneumatiques de qualité supérieure.
            ")
            ->setPrice(80)
            ->setPublished(true)
            ->addImageService($image4);
        copy("src/DataFixtures/images/services/Pneumatique.webp", "public/media/uploads/{$image4->getFilename()}");
        $manager->persist($service4);

        /** Service 5 */
        $image5 = new ImageService();
        $image5
            ->setAlt('Freinage disque et plaquettes')
            ->setFilename('Plaquettes de frein.webp');
        $service5 = new Service();
        $service5
            ->setName('Freinage - disque et/ou plaquettes')
            ->setDescription("
            La sécurité de votre véhicule dépend en grande partie de la performance de votre système de freinage. 
            Notre service de freinage et de remplacement de disque de frein est conçu pour garantir un freinage efficace, 
            sans compromis. Nos techniciens expérimentés utilisent uniquement des pièces de rechange de haute qualité 
            pour assurer la réactivité de vos freins dans toutes les situations. Vous pouvez compter sur nous pour maintenir 
            vos freins en parfait état, vous offrant une tranquillité d'esprit à chaque trajet.
            ")
            ->setPrice(80)
            ->setPublished(true)
            ->addImageService($image5);
        copy("src/DataFixtures/images/services/Plaquettes de frein.webp", "public/media/uploads/{$image5->getFilename()}");
        $manager->persist($service5);

        $garage
            ->addService($service1)
            ->addService($service2)
            ->addService($service3)
            ->addService($service4)
            ->addService($service5);


        /**
         * Création d'un message de contact
         */
        $contactMessage = new ContactMessage();
        $contactMessage
            ->setFirstname('Gervaise')
            ->setLastname('Macquart')
            ->setEmail('gervaise.macquart@wanadoo.fr')
            ->setMessage("Bonjour, je souhaiterais savoir si vous proposez des véhicules pendant le temps de réparation ?")
            ->setPhoneNumber('0645291499')
            ->setTermsAccepted(true)
            ->setSubject('Demande d\'informations')
        ;
        $manager->persist($contactMessage);

        /**
         * Création d'un témoignage
         */
        $avis1 = new Testimonial();
        $avis1
            ->setAuthor("Jean-Luc")
            ->setComment("Toujours souriant et profesionnel. j'approuve !")
            ->setNote(5)
            ->isApproved(false)
        ;
        $manager->persist($avis1);

        $manager->flush();
    }
}