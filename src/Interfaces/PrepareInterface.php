<?php

namespace EmailTemplate\Interfaces;

interface PrepareInterface
{
    /**
     * Given the template, and the data for it, this function
     * will return the output for the template
     *
     * @param string $template
     * @param array $data
     * @return string
     */
    public function render($template, array $data);
}
