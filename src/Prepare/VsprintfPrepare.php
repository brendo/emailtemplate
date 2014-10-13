<?php

namespace EmailTemplate\Prepare;

use EmailTemplate\Interfaces as Interfaces;

class VsprintfPrepare implements Interfaces\PrepareInterface
{
    /**
     * {@inheritDoc}
     */
    public function render($template, array $data)
    {
        return vsprintf($template, $data);
    }
}
