<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 29.08.14
 * Time: 10:54
 */

namespace Wk\BaseBundle\Tests\Document;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Wk\BaseBundle\Document\Document;
use JMS\Serializer\Serializer;

/**
 * Class DocumentTest
 *
 * @package Wk\BaseBundle\Tests\Document
 */
class DocumentTest extends WebTestCase
{
    /**
     * Tests serialization of a base document
     */
    public function testSerialization()
    {
        $timeZone = new \DateTimeZone('Europe/Berlin');

        /** @var Document $document */
        $document = $this->getMockForAbstractClass('WK\BaseBundle\Document\Document');
        $document->setId('1');
        $document->setCreatedAt(new \DateTime('2012-07-08 11:14:15', $timeZone));
        $document->setModifiedAt(new \DateTime('2007-12-14 15:09:29', $timeZone));

        $client = static::createClient();
        /** @var Serializer $serializer */
        $serializer = $client->getContainer()->get('jms_serializer');

        $this->assertJsonStringEqualsJsonFile(
            __DIR__ . '/../Data/document.json',
            $serializer->serialize($document, 'json')
        );
    }
}
 