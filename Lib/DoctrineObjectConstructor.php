<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 06.03.15
 * Time: 16:30
 */

namespace Wk\BaseBundle\Lib;

use Doctrine\Common\Persistence\ManagerRegistry;
use JMS\Serializer\Construction\ObjectConstructorInterface;
use JMS\Serializer\VisitorInterface;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\DeserializationContext;

/**
 * Class DoctrineObjectConstructor
 *
 * Doctrine object constructor for new (or existing) objects during deserialization.
 *
 * @package Wk\BaseBundle\Lib
 */
class DoctrineObjectConstructor implements ObjectConstructorInterface
{
    private $managerRegistry;
    private $fallbackConstructor;

    /**
     * Constructor.
     *
     * @param ManagerRegistry            $managerRegistry     Manager registry
     * @param ObjectConstructorInterface $fallbackConstructor Fallback object constructor
     */
    public function __construct(ManagerRegistry $managerRegistry, ObjectConstructorInterface $fallbackConstructor)
    {
        $this->managerRegistry = $managerRegistry;
        $this->fallbackConstructor = $fallbackConstructor;
    }

    /**
     * {@inheritdoc}
     */
    public function construct(VisitorInterface $visitor, ClassMetadata $metadata, $data, array $type, DeserializationContext $context)
    {
        // Locate possible ObjectManager
        $objectManager = $this->managerRegistry->getManagerForClass($metadata->name);

        if (!$objectManager) {
            // No ObjectManager found, proceed with normal deserialization
            return $this->fallbackConstructor->construct($visitor, $metadata, $data, $type, $context);
        }

        // Locate possible ClassMetadata
        $classMetadataFactory = $objectManager->getMetadataFactory();

        if ($classMetadataFactory->isTransient($metadata->name)) {
            // No ClassMetadata found, proceed with normal deserialization
            return $this->fallbackConstructor->construct($visitor, $metadata, $data, $type, $context);
        }

        // Managed entity, check for proxy load
        if (!is_array($data)) {
            // Single identifier, load proxy
            return $objectManager->getReference($metadata->name, $data);
        }

        // Fallback to default constructor if missing identifier(s)
        $classMetadata = $objectManager->getClassMetadata($metadata->name);
        $identifier = null;

        foreach ($classMetadata->getIdentifierFieldNames() as $name) {
            if (array_key_exists($name, $data)) {
                $identifier = $data[$name];

                break;
            }
        }

        // Entity update, try to load it from database
        $object = $objectManager->find($metadata->name, $identifier);

        if (!is_object($object)) {
            // Entity with that identifier didn't exist, create a new Entity
            $reflection = new \ReflectionClass($metadata->name);
            $object = $reflection->newInstance();
        }

        $objectManager->initializeObject($object);

        return $object;
    }
}
