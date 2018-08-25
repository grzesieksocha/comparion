<?php declare(strict_types=1);

namespace App\Controller;

use Psr\Log\InvalidArgumentException;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\JsonObjectSerializer;
use App\Resource\Repository;
use App\Criteria\PullCriteria;
use App\Criteria\RepositoryCriteria;
use App\Factory\ComparisonFactory;
use App\Factory\RepositoryFactory;
use App\Service\ApiDataRetriever;
use App\Service\Fields;
use App\Validator\ParametersResolver;

/**
 * @Route("/api")
 */
class RepositoryController
{
    /** @var RepositoryFactory */
    private $repositoryFactory;
    /** @var ComparisonFactory */
    private $comparisonFactory;
    /** @var JsonObjectSerializer */
    private $jsonObjectSerializer;

    /**
     * @Route("/repository/{identifier}", methods={"GET"}, name="get_repository", requirements={"identifier"=".+"})
     */
    public function getRepositoryAction(
        string $identifier,
        Request $request,
        RepositoryFactory $repositoryFactory,
        ComparisonFactory $comparisonFactory,
        ApiDataRetriever $apiDataRetriever,
        JsonObjectSerializer $jsonObjectSerializer)
    {
        $this->repositoryFactory = $repositoryFactory;
        $this->comparisonFactory = $comparisonFactory;
        $this->jsonObjectSerializer = $jsonObjectSerializer;
        try {
            $repository = $repositoryFactory->getRepository($identifier);
            if ($request->query) {
                return $this->processCompareRequest($repository, $request->query);
            }
            $apiDataRetriever->fill($repository);
            $serializedRepository = $jsonObjectSerializer->serialize($repository);
        } catch (\Exception $e) {
            return $this->getBadRequestResponse($e->getMessage());
        }

        return $this->getSuccessResponse($serializedRepository);
    }

    /**
     * @Route("/repository_comparison/by_repository", methods={"GET"}, name="compare_by_repository")
     */
    public function compareByRepositoryAction(
        Request $request,
        ParametersResolver $parametersResolver,
        RepositoryFactory $repositoryFactory,
        ComparisonFactory $comparisonFactory,
        ApiDataRetriever $apiDataRetriever): Response
    {
        try {
            // TODO move this part away from the controller
            $repositories =
                $this->getRepositoriesData($request->query, $parametersResolver, $repositoryFactory, $apiDataRetriever);
        } catch (InvalidArgumentException $e) {
            return $this->getBadRequestResponse($e->getMessage());
        }

        $result = $comparisonFactory->getComparisonByRepository($repositories);

        return $this->getSuccessResponse($result);
    }

    /**
     * @Route("/repository_comparison/by_property", methods={"GET"}, name="compare_by_property")
     */
    public function compareByPropertyAction(
        Request $request,
        ParametersResolver $parametersResolver,
        RepositoryFactory $repositoryFactory,
        ComparisonFactory $comparisonFactory,
        ApiDataRetriever $apiDataRetriever): Response
    {
        try {
            // TODO move this part away from the controller
            $repositories =
                $this->getRepositoriesData($request->query, $parametersResolver, $repositoryFactory, $apiDataRetriever);
        } catch (InvalidArgumentException $e) {
            return $this->getBadRequestResponse($e->getMessage());
        }

        $result = $comparisonFactory->getComparisonByProperty($repositories);

        return $this->getSuccessResponse($result);
    }

    /**
     * @return Repository[]
     */
    private function getRepositoriesData(
        ParameterBag $query,
        ParametersResolver $parametersResolver,
        RepositoryFactory $repositoryFactory,
        ApiDataRetriever $apiDataRetriever): array
    {
        $params = $parametersResolver->resolve($query);
        $repositories = [
            $repositoryOne = $repositoryFactory->getRepository($params['repoOne']),
            $repositoryTwo = $repositoryFactory->getRepository($params['repoTwo'])
        ];

        $this->setBasicData($repositories, $apiDataRetriever);
        $this->setPullRequestData($repositories, $apiDataRetriever);

        return $repositories;
    }

    /**
     * @param Repository[] $repositories
     */
    private function setBasicData(array $repositories, ApiDataRetriever $apiDataRetriever): void
    {
        $fields = new Fields();
        $fields->addField(Fields::FORKS_COUNT);
        $fields->addField(Fields::STARGAZERS_COUNT);
        $fields->addField(Fields::UPDATED_AT);

        $apiDataRetriever->fill($repositories, new RepositoryCriteria(), $fields);
    }

    /**
     * @param Repository[] $repositories
     */
    private function setPullRequestData(array $repositories, ApiDataRetriever $apiDataRetriever): void
    {
        $fields = new Fields();
        $fields->addField(Fields::STATE);

        $apiDataRetriever->fill($repositories, new PullCriteria(), $fields);
    }

    private function getBadRequestResponse(string $message): Response
    {
        $response = new Response();
        $response->setContent(json_encode(['message' => $message]));
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);

        return $response;
    }

    private function getSuccessResponse(string $result): Response
    {
        $response = new Response();
        $response->setContent($result);
        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    private function processCompareRequest(Repository $repositoryOne, ParameterBag $query)
    {
        $repositoryTwo = $this->repositoryFactory->getRepository($query->get('compare'));
        $comparison = $this->comparisonFactory->compare($repositoryOne, $repositoryTwo);
        $serializedComparison = $this->jsonObjectSerializer->serialize($comparison);
        return $this->getSuccessResponse($serializedComparison);
    }
}