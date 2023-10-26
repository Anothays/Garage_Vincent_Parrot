<?php

namespace App\DataFixtures;

use App\Entity\Car;
use App\Entity\Garage;
use App\Entity\ImageCar;
use App\Entity\ImageService;
use App\Entity\Schedule;
use App\Entity\Service;
use App\Entity\User;
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
         * Création d'un employé
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

        /** Service 1 */
        $image1 = new ImageService();
        $image1
            ->setAlt('Entretien et vidange')
            ->setFilename('Entretien et vidange.webp');
        $service1 = new Service();
        $service1
            ->setName('Entretien et vidange')
            ->setDescription($this->faker->text(300))
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
            ->setDescription($this->faker->text(300))
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
            ->setDescription($this->faker->text(300))
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
            ->setDescription($this->faker->text(300))
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
            ->setDescription($this->faker->text(300))
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



        $manager->flush();
    }
}