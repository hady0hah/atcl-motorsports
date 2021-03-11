<?php


namespace App\Listener;


use App\Entity\Result;
use App\Entity\ResultTime;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class ResultListener
{
    public function preUpdate(LifecycleEventArgs $args): void
    {
        $result = $args->getObject();
        if (!$result instanceof Result) {
            return;
        }
            $changeSet = $args->getEntityManager()->getUnitOfWork()->getEntityChangeSet($result);
            if (isset($changeSet['value']))
            {
                $result->setValueNumberAutomatic();
            }
    }
}