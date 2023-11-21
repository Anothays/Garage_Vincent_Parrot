<?php

namespace App\Tests\Entity;

use App\Entity\Car;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\Error;
use PHPUnit\Util\Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CarRepositoryTest extends KernelTestCase
{
    private $em;
    private $carRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->em = $container->get(EntityManagerInterface::class);
        $this->carRepository = $this->em->getRepository(Car::class);
    }

    public function testFindByFilters(): void
    {

        $value = ["mileageMin" => "1", "mileageMax" => "300000", "priceMin" => "10" , "priceMax" => "1000000", "yearMin" => "2000", "yearMax" => "2023"];
        $cars = $this->carRepository->findByFilters($value, 1);
        $this->assertIsArray($cars, 'Not an array');
    }




}
