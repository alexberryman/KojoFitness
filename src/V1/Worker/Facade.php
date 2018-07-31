<?php
declare(strict_types=1);

namespace Neighborhoods\KojoExamples\V1\Worker;

use Neighborhoods\KojoExamples\V1\WorkerInterface;
use Neighborhoods\Pylon\DependencyInjection;
use Symfony\Component\Finder\Finder;
use Neighborhoods\Kojo\Api;

class Facade implements FacadeInterface
{
    use Api\V1\Worker\Service\AwareTrait;
    protected $containerBuilderFacade;
    protected $worker;
    protected $isBootStrapped = false;

    public function start(): FacadeInterface
    {
        $this->bootstrap();
        $this->getWorker()->work();

        return $this;
    }

    protected function bootstrap(): FacadeInterface
    {
        if ($this->isBootStrapped !== false) {
            throw new \LogicException('Worker facade is already bootstrapped.');
        }
        $containerBuilderFacade = $this->getContainerBuilderFacade();
        $discoverableDirectories[] = __DIR__ . '/../../../src';
        $finder = new Finder();
        $finder->name('*.yml');
        $finder->files()->in($discoverableDirectories);
        $containerBuilderFacade->addFinder($finder);
        $containerBuilder = $containerBuilderFacade->getContainerBuilder();
        $this->setWorker($containerBuilder->get(WorkerInterface::class));
        $this->isBootStrapped = true;

        return $this;
    }

    public function getContainerBuilderFacade(): DependencyInjection\ContainerBuilder\FacadeInterface
    {
        if ($this->containerBuilderFacade === null) {
            $this->containerBuilderFacade = new DependencyInjection\ContainerBuilder\Facade();
        }

        return $this->containerBuilderFacade;
    }

    protected function getWorker(): WorkerInterface
    {
        if ($this->worker === null) {
            throw new \LogicException('Worker is not set.');
        }

        return $this->worker;
    }

    protected function setWorker(WorkerInterface $worker): FacadeInterface
    {
        if ($this->worker !== null) {
            throw new \LogicException('Worker is already set.');
        }
        $this->worker = $worker->setApiV1WorkerService($this->getApiV1WorkerService());

        return $this;
    }
}