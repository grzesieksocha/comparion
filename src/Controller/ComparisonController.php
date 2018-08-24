<?php declare(strict_types=1);

namespace App\Controller;

use Psr\Log\InvalidArgumentException;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Resource\Repository;
use App\Criteria\PullCriteria;
use App\Criteria\RepositoryCriteria;
use App\Resource\ComparisonFactory;
use App\Resource\RepositoryFactory;
use App\Service\ApiDataRetriever;
use App\Service\Fields;
use App\Validator\ParametersResolver;

/**
 * @Route("/api")
 */
class ComparisonController
{
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

    /**
     * @param mixed[] $result
     */
    private function getSuccessResponse(array $result): Response
    {
        $response = new Response();
        $response->setContent(json_encode($result));
        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}