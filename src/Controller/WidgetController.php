<?php

/*
 * This file is part of eReolen widget.
 *
 * (c) 2018 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace App\Controller;

use App\Entity\Widget;
use App\Service\EreolenSearch;
use App\Service\SearchException;
use App\Service\WidgetStatisticsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGenerator;

/**
 * @Route("/widget")
 */
class WidgetController extends AbstractController
{
    /** @var \App\Service\EreolenSearch */
    private $search;

    /** @var \App\Service\WidgetStatisticsService */
    private $statistics;

    public function __construct(EreolenSearch $search, WidgetStatisticsService $statistics)
    {
        $this->search = $search;
        $this->statistics = $statistics;
    }

    /**
     * Wrapper around (ie. proxy for) the real search on ereolen.dk.
     *
     * @Route("/search", name="widget_search")
     */
    public function search(Request $request)
    {
        $query = $request->query->all();

        try {
            $result = $this->search->search($query);

            if (isset($result['links'])) {
                $result['links'] = array_map(function ($url) {
                    $info = parse_url($url);
                    parse_str($info['query'] ?? '', $query);

                    return $this->generateUrl(
                        'widget_search',
                        $query,
                        UrlGenerator::ABSOLUTE_URL
                    );
                }, $result['links']);
            }

            return new JsonResponse($result);
        } catch (SearchException $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }

    /**
     * @Route("/{id}", name="widget_show")
     */
    public function show(Request $request, Widget $widget)
    {
        $this->statistics->addRequest($widget, $request);

        return $this->render('widget/show.html.twig', ['widget' => $widget]);
    }

    /**
     * @Route("/{id}/edit", name="widget_edit")
     */
    public function edit(Request $request, Widget $widget)
    {
        return $this->render('default/index.html.twig', ['widget' => $widget]);
    }

    /**
     * @Route("/{id}/embed", name="widget_embed")
     */
    public function embed(Request $request, Widget $widget)
    {
        $this->statistics->addRequest($widget, $request);

        $configuration = $widget->getConfiguration();

        if (isset($configuration['search']['type'], $configuration['search']['url']) && 'url' === $configuration['search']['type']) {
            try {
                $result = $this->search->search(['url' => $configuration['search']['url']]);
                if (isset($result['data'])) {
                    $widget->setContent($result['data']);
                }
            } catch (SearchException $exception) {
            }
        }

        return $this->render('widget/embed.html.twig', ['widget' => $widget]);
    }

    /**
     * @Route("/{id}/redirect", name="widget_redirect")
     */
    public function doRedirect(Request $request, Widget $widget)
    {
        $url = $request->get('url');
        if (empty($url)) {
            throw new BadRequestHttpException('Invalid url: '.($url ?? '(empty)'));
        }

        $this->statistics->addRedirectRequest($widget, $url, $request);

        return $this->redirect($url);
    }

    /**
     * @Route("/{id}/statistics", name="widget_statistics")
     */
    public function statistics(Request $request, Widget $widget)
    {
        $statistics = $this->statistics->getStatistics($widget);

        return new JsonResponse($statistics);
    }
}
