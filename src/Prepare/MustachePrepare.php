<?php

namespace EmailTemplate\Prepare;

use EmailTemplate\Interfaces as Interfaces;
use Mustache_Engine as Mustache;

class MustachePrepare implements Interfaces\PrepareInterface
{
    /**
     * {@inheritDoc}
     */
    public function render($template, array $data)
    {
        $m = new Mustache;
        return $m->render($template, $data);
    }
}
