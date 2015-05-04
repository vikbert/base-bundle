<?php

namespace Wk\BaseBundle\Controller;

use Doctrine\ODM\MongoDB\DocumentRepository;
use FOS\RestBundle\Controller\Annotations as RestBundle;
use FOS\RestBundle\Request\ParamFetcher;
use JMS\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Wk\BaseBundle\Lib\RequestParam;

/**
 * Class CrudController
 */
abstract class CrudController extends Controller
{
    /**
     * @var DocumentManager
     */
    protected $manager;

    /**
     * @var DocumentRepository
     */
    protected $repository;

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * Abstract method which has to be created by inheriting classes
     *
     * @return string
     */
    abstract protected function getRepositoryClass();

    /**
     * Abstract method which has to be created by inheriting classes
     *
     * @return string
     */
    abstract protected function getDocumentClass();

    /**
     * Overwrite to ensure an existing container for getting the MongoDB service for each method
     *
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);

        $this->serializer = $this->get('jms_serializer');
        $this->manager = $this->get('doctrine_mongodb')->getManager();
        $this->repository = $this->manager->getRepository($this->getRepositoryClass());

        // Tell the document manager to create indexes
        $this->manager->getSchemaManager()->ensureDocumentIndexes($this->getDocumentClass());
    }

    /**
     * Gets a single document by its id
     * Exemplary call: GET /devices/{identifier}.json
     *
     * @param string $identifier
     *
     * @return object
     * @RestBundle\View
     */
    public function getAction($identifier)
    {
        return $this->findDocumentById($identifier);
    }

    /**
     * Gets a collection of documents
     * Exemplary call: GET /devices.json?filter[{field}]={value}&sort[{field}]={asc/desc}&limit=2&offset=5
     *
     * @param ParamFetcher $paramFetcher
     *
     * @RestBundle\QueryParam(name="offset", requirements="\d+", default="0", description="Offset from which to start listing documents.")
     * @RestBundle\QueryParam(name="limit", requirements="\d+", default="10", description="How many documents to return.")
     * @RestBundle\QueryParam(name="filter", array=true, nullable=true, description="filter for document listing.")
     * @RestBundle\QueryParam(name="sort", array=true, nullable=true, description="Attribute to sort with")
     *
     * @return array
     * @RestBundle\View
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        $filter = $paramFetcher->get('filter');
        $sort = $paramFetcher->get('sort');
        $limit = intval($paramFetcher->get('limit'));
        $offset = intval($paramFetcher->get('offset'));

        // Converts the string values to it's real types (int, bool, ...)
        $filter = RequestParam::convertType($filter);

        // Except a Mongo ID (must be a string also if it's a INCREMENT)
        foreach ($filter as $key => $value) {
            if (preg_match('/^(.+\.|)id$/', $key)) {
                $filter[$key] = strval($value);
            }
        }

        return $this->repository->findBy($filter, $sort, $limit, $offset);
    }

    /**
     * Creates new documents
     * Exemplary call: POST /devices.json (add devices serialized as json to the request body)
     *
     * @param Request $request
     *
     * @return object|array
     * @RestBundle\View
     */
    public function cpostAction(Request $request)
    {
        try {
            $documents = $this->deserializePayload($request, $this->getDocumentClass(), true);
        } catch (BadRequestHttpException $exception) {
            // Supports backwards compatibility which accepts just one document which isn't wrapped into an array
            $document = $this->deserializePayload($request, $this->getDocumentClass());

            $this->manager->persist($document);
            $this->manager->flush($document);
            $this->manager->refresh($document);

            return $document;
        }

        foreach ($documents as $document) {
            $this->manager->persist($document);
        }

        $this->manager->flush();

        foreach ($documents as $document) {
            $this->manager->refresh($document);
        }

        return $documents;
    }

    /**
     * Updates an existing document
     * Exemplary call: PUT /devices/{identifier}.json (add device serialized as json to the request body)
     *
     * @param Request $request
     * @param string  $identifier
     *
     * @return object
     * @RestBundle\View
     */
    public function putAction(Request $request, $identifier)
    {
        // Just to check if it's really an existing document (throws 404 if not)
        $this->findDocumentById($identifier);

        $document = $this->deserializePayload($request, $this->getDocumentClass());
        $document->setId($identifier);
        $document = $this->manager->merge($document);

        $this->manager->flush($document);
        $this->manager->refresh($document);

        return $document;
    }

    /**
     * Deletes an existing document
     * Exemplary call: DELETE /devices/{identifier}.json
     *
     * @param string $identifier
     *
     * @return object
     * @RestBundle\View
     */
    public function deleteAction($identifier)
    {
        $document = $this->findDocumentById($identifier);

        if ($document) {
            $this->manager->remove($document);
            $this->manager->flush($document);
        }

        return $document;
    }

    /**
     * @param Request $request    Request object
     * @param string  $type       Class name with namespace
     * @param bool    $collection Single document or a collection
     *
     * @return object|array
     */
    protected function deserializePayload(Request $request, $type, $collection = false)
    {
        $payload = $request->getContent();

        if (!$payload) {
            throw $this->createBadRequestException('No payload found');
        }

        try {
            if ($collection) {
                $type = sprintf('array<%s>', $type);

                $decoded = json_decode($payload);
                if (!is_array($decoded)) {
                    throw new \Exception('Payload contains no collection');
                }
            }

            return $this->serializer->deserialize($payload, $type, 'json');
        } catch (\Exception $exception) {
            throw $this->createBadRequestException($exception->getMessage(), $exception);
        }
    }

    /**
     * Helper to find a document
     *
     * @param string $identifier
     *
     * @return object
     *
     * @throws NotFoundHttpException
     */
    protected function findDocumentById($identifier)
    {
        $document = $this->repository->find($identifier);

        if (is_null($document)) {
            throw $this->createNotFoundException("No document found for id $identifier");
        }

        return $document;
    }
}
