<?php

namespace App\Controller;

use Shopify\Clients\Graphql;
use Shopify\Clients\Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController.
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Session $session, Request $request) {
        return new Response("<h3>Shopify App with Symfony 🎉😃</h3>");
    }

    /**
     * @Route("/rest2", name="rest")
     */
    public function rest(Session $session, Request $request) {
        $shop = $session->get('shop');
        $accessToken = $session->get('accessToken');
        // REST call
        $client = new Rest($shop, $accessToken);
        $response = $client->get("orders");
        dd($response->getDecodedBody());
        return new Response("<h3>Rest API call</h3>");
    }

    /**
     * @Route("/graph", name="graph")
     */
    public function graph(Session $session, Request $request) {
        $shop = $session->get('shop');
        $accessToken = $session->get('accessToken');
        // QraphQL call
        $client = new Graphql($shop, $accessToken);

        $dateStart = '2021-11-01';
        $dateEnd = '2021-11-30';
        $queryString = <<<QUERY
            {
                orders(first: 50, query: "created_at:>=$dateStart AND created_at:<=$dateEnd OR created_at:=$dateStart OR created_at:=$dateEnd") {
                    edges {
                        node {
                            id
                            name
                            totalPriceSet {
                                shopMoney {
                                    amount
                                }
                            }
                        }
                    }
                }
            }
        QUERY;

        $variables = [];
        dump($variables);
        $response = $client->query([
            'query' => $queryString
        ]);
        dd($response->getDecodedBody());
        return new Response("<h3>Graph API call</h3>");
    }
}
