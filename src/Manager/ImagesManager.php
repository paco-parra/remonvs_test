<?php
declare(strict_types=1);

namespace App\Manager;

use App\Entity\Images;

class ImagesManager extends AbstractBaseManager
{

    public function createOrRetrieveBy(array $findKey)
    {
        $image = $this->entityManager->getRepository($this->class)->findOneBy([$findKey['key'] => $findKey['value']]);

        if(!$image instanceof Images) {
            $image = $this->create();
            $image->setUrl($findKey['value']);
            $this->save($image, true);
        }
        return $image;
    }
}