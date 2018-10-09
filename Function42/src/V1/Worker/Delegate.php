<?php
declare(strict_types=1);

namespace Neighborhoods\KojoFitnessFunction42\V1\Worker;

use Neighborhoods\KojoFitnessFunction42\V1\Worker;

class Delegate implements DelegateInterface
{
    use Worker\Queue\Message\AwareTrait;

    public function businessLogic() : DelegateInterface
    {
        if ((bool)random_int(0, 1)) {
            $this->getV1WorkerQueueMessage()->delete();
        } else {
            throw new \TypeError("Didn't get lucky");
        }

        return $this;
    }
}
