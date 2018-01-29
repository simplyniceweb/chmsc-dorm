<?php

namespace forms;

use Symfony\Component\Form\AbstractExtension;

class TypesExtension extends AbstractExtension
{
    var $em;
    
    public function __construct($em)
    {
        $this->em = $em;
    }

    protected function loadTypes()
    {
        return array(
            new types\Student($this->em),
        );

    }

}