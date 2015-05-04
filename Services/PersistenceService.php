<?php

namespace Wk\BaseBundle\Services;

use Monolog\Logger;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Class PersistenceService
 *
 * @package Wk\RestBundle\Services
 */
abstract class PersistenceService extends ContainerAware
{
    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @param ObjectManager $manager
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param Logger $logger
     *
     * @return $this
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * @return Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @return ObjectManager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @param ObjectManager $manager
     *
     * @return $this
     */
    public function setManager($manager)
    {
        $this->manager = $manager;

        return $this;
    }
}
