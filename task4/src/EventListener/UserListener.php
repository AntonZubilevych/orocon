<?php

namespace App\EventListener;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;


class UserListener implements  LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function onFlush(OnFlushEventArgs $args) {

        $entityManager = $args->getEntityManager();
        $unitOfWork = $entityManager->getUnitOfWork();
        $updatedEntities = $unitOfWork->getScheduledEntityUpdates();

        foreach ($updatedEntities as $updatedEntity) {

            if (!$updatedEntity instanceof User) {
                return;
            }

            $changeset = $unitOfWork->getEntityChangeSet($updatedEntity);

            if (!(array_key_exists('email', $changeset))) {
                $this->logger->alert('Email  Not Updated');
                return;
            }

            $changes = $changeset['email'];
            $previousValueForField = array_key_exists(0, $changes) ? $changes[0] : null;
            $newValueForField = array_key_exists(1, $changes) ? $changes[1] : null;

            if ($previousValueForField != $newValueForField) {
                $this->logger->alert('Email Updated');
            }


        }
    }
}
