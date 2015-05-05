<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 19.12.14
 * Time: 12:33
 */

namespace Wk\BaseBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * Class DocumentManagerAwareCommand
 *
 * @package Wk\BaseBundle\Command
 */
abstract class DocumentManagerAwareCommand extends ContainerAwareCommand
{
    /**
     * @var DocumentManager $documentManager
     */
    private $documentManager;

    /**
     * @return DocumentManager
     */
    public function getDocumentManager()
    {
        if ($this->documentManager === null) {
            $this->documentManager = $this->getContainer()->get('doctrine.odm.mongodb.document_manager');
        }

        return $this->documentManager;
    }

    /**
     * @param DocumentManager $documentManager
     */
    public function setDocumentManager($documentManager)
    {
        $this->documentManager = $documentManager;
    }
}

