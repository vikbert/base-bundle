<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 05.08.14
 * Time: 16:40
 */

namespace Wk\BaseBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation as Serializer;
use Gedmo\Mapping\Annotation as Gedmo;
use Wk\BaseBundle\Lib\Object;
use DateTime;

/**
 * Class BaseDocument
 *
 * @package Wk\BaseBundle\Document
 */
abstract class Document extends Object
{
    /**
     * Mandatory ID
     *
     * @MongoDB\Id
     * @Serializer\Type("string")
     * @var string
     */
    protected $id;

    /**
     * @Gedmo\Timestampable(on="create")
     * @MongoDB\Date
     * @Serializer\Type("DateTime")
     * @var DateTime
     */
    protected $createdAt;

    /**
     * @Gedmo\Timestampable(on="update")
     * @MongoDB\Date
     * @Serializer\Type("DateTime")
     * @var DateTime
     */
    protected $modifiedAt;

    /**
     * Getter for ID
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Setter for ID
     *
     * @param string $id
     *
     * @return $this;
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     *
     * @return $this
     */
    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    /**
     * @param DateTime $modifiedAt
     *
     * @return $this
     */
    public function setModifiedAt(DateTime $modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }
}
