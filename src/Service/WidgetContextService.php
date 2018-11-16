<?php

/*
 * This file is part of eReolen widget.
 *
 * (c) 2018 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class WidgetContextService
{
    private $requestStack;

    private $contexts = [
        'ereolen' => [
            'name' => 'ereolen',
            'label' => 'eReolen',
            'url' => 'https://www.ereolen.dk',
            'searchLink' => '<a href="https://ereolen.dk/search/ting" target="_blank">eReolen</a>',
            'logo' => 'https://ereolen.dk/sites/all/themes/orwell/svg/eReolen_Logo.svg',
            'searchUrl' => 'https://www.ereolen.dk/search/ting',
            'ereol_widget_search_url' => 'https://itk:itk@stg.ereolen.dk',
        ],
        'ereolengo' => [
            'name' => 'ereolengo',
            'label' => 'eReolen GO',
            'url' => 'https://www.ereolengo.dk',
            'searchLink' => '<a href="https://ereolengo.dk/search/ting" target="_blank">eReolen GO</a>',
            'logo' => 'https://ereolengo.dk/sites/all/themes/wille/svg/logo.svg',
            'searchUrl' => 'https://www.ereolengo.dk/search/ting',
            'ereol_widget_search_url' => 'https://itk:itk@stg.ereolen.dk',
        ],
    ];

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getContext()
    {
        $request = $this->requestStack->getCurrentRequest();
        $name = $request->get('context');

        return $this->contexts[$name] ?? null;
    }

    public function getContexts()
    {
        return $this->contexts;
    }
}
