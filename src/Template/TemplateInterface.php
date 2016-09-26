<?php

namespace Bence\Template;

/**
 * Interface TemplateInterface
 *
 * @author Bence Borbély
 */
interface TemplateInterface
{

    /**
     * @param string $template
     * @param array $data
     * @return string
     */
    public function render($template, array $data);

}
