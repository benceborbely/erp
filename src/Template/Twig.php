<?php

namespace Bence\Template;

/**
 * Class Twig
 *
 * @author Bence Borbély
 */
class Twig implements TemplateInterface
{

    /**
     * @var \Twig_Loader_Filesystem
     */
    protected $loader;

    /**
     * @var \Twig_Environment
     */
    protected $environment;

    /**
     * @param \Twig_Loader_Filesystem $loader
     * @param \Twig_Environment $environment
     */
    public function __construct(\Twig_Loader_Filesystem $loader, \Twig_Environment $environment)
    {
        $this->loader = $loader;
        $this->environment = $environment;
    }

    /**
     * @param string $template
     * @param array $data
     * @return string
     */
    public function render($template, array $data)
    {
        return $this->environment->render($template, $data);
    }

}
