<?php

namespace EmailTemplate\Interfaces;

interface TemplateInterface
{
    /**
     * Given a handle, the function will look for an email template to
     * load from the `/templates` directory. It is expected that the template
     * name will be `$handle.html`.
     *
     * @throws \InvalidArgumentException when $handle is not provided
     * @throws \RuntimeException when the template can not be found
     * @param string $handle
     * @return string
     */
    public function load($handle = null);

    /**
     * This preforms a merge of the given `$data` with the placeholders
     * in the template. The `$render` will determine how the template
     * is rendered.
     *
     * @param PrepareInterface $render
     * @param array $data
     * @return string
     */
    public function prepare(PrepareInterface $render, array $data);
}
