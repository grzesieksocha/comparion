<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Resource\Repository;

/**
 * @Route("/api")
 */
class RepositoryController extends Controller
{
    /**
     * @Route("/repository/{identifier}", methods={"GET"}, name="get_repository", requirements={"identifier"=".+"})
     *
     * @return Response
     */
    public function getRepositoryAction(Request $request, string $identifier) : Response
    {
        $repositoryFactory = $this->container->get('app.repository_factory');
        $apiDataRetriever = $this->container->get('app.api_data_retriever');
        $jsonObjectSerializer = $this->container->get('app.json_object_serializer');

        try {
            $repository = $repositoryFactory->getRepository($identifier);
            $apiDataRetriever->fill($repository);

            $repositoryToCompare = $request->query->get('compare');
            if (!empty($repositoryToCompare)) {
                return $this->processCompareRequest($repository, $repositoryToCompare);
            }

            $serializedRepository = $jsonObjectSerializer->serialize($repository);
            return $this->getSuccessResponse($serializedRepository);
        } catch (\Exception $e) {
            return $this->getBadRequestResponse($e->getMessage());
        }
    }

    private function processCompareRequest(Repository $repositoryOne, string $secondRepoIdentifier)
    {
        $repositoryFactory = $this->container->get('app.repository_factory');
        $repositoryTwo = $repositoryFactory->getRepository($secondRepoIdentifier);

        $apiDataRetriever = $this->container->get('app.api_data_retriever');
        $apiDataRetriever->fill($repositoryTwo);

        $comparisonFactory = $this->container->get('app.comparison_factory');
        $comparison = $comparisonFactory->compare($repositoryOne, $repositoryTwo);

        $jsonObjectSerializer = $this->container->get('app.json_object_serializer');
        $serializedComparison = $jsonObjectSerializer->serialize($comparison);
        return $this->getSuccessResponse($serializedComparison);
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
}