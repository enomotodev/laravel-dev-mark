<?php

namespace LaravelDevMark;

use Symfony\Component\HttpFoundation\Response;

class LaravelDevMark
{
    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    public function __construct($app)
    {
        $this->app = $app;
        $this->config = $this->app['config'];
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->config->get('laravel-dev-mark.enabled');
    }

    /**
     * @param  Response $response
     * @return void
     */
    public function modifyResponse(Response $response)
    {
        $content = $response->getContent();

        $env = $this->app['env'];
        $options = $this->config->get('laravel-dev-mark');

        $class = $options['position'];
        if ($options['fixed']) {
            $class .= ' fixed';
        }

        $renderedContent = <<<HTML
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/github-fork-ribbon-css/0.2.3/gh-fork-ribbon.min.css" />
<div class="github-fork-ribbon {$class}" data-ribbon="{$env}" onclick="this.style.display = 'none';">{$env}</div>
HTML;

        $pos = strripos($content, '</body>');
        if (false !== $pos) {
            $content = substr($content, 0, $pos) . $renderedContent . substr($content, $pos);
        } else {
            $content = $content . $renderedContent;
        }

        $response->setContent($content);
        $response->headers->remove('Content-Length');
    }
}
