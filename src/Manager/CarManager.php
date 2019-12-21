<?php
declare(strict_types=1);

namespace App\Manager;

use App\Entity\Car;

class CarManager extends AbstractBaseManager
{

    public function createOrRetrieveByUrl(array $findKey)
    {
        $car = $this->entityManager->getRepository($this->class)->findOneBy([$findKey['key'] => $findKey['value']]);

        if(!$car instanceof Car) {
            $car = $this->create();
            $car->setExternalUrl($findKey['value']);
            $this->save($car, true);
        }
        return $car;
    }
}