<?php

/**
 * @see       https://github.com/laminas/laminas-form for the canonical source repository
 * @copyright https://github.com/laminas/laminas-form/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-form/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Form\Integration\TestAsset;

use Laminas\Form\Form as BaseForm;
use Laminas\Form\FormElementManager;

class Form extends BaseForm
{
    /**
     * @param null|FormElementManager
     */
    public $elementManagerAtInit;

    /**
     * {@inheritDoc}
     */
    public function init()
    {
        $this->elementManagerAtInit = $this->getFormFactory()->getFormElementManager();
    }
}
