<?php

namespace Brajola\ForceLogin\Block\Adminhtml\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

/**
 * Class Urls
 * @package Brajola\ForceLogin\Block\Adminhtml\Form\Field
 */
class Urls extends AbstractFieldArray
{
    /**
     * Prepare to Render
     */
    protected function _prepareToRender()
    {
        $this->addColumn('name', ['label' => __('Name'), 'class' => 'required-entry']);
        $this->addColumn('url', ['label' => __('URL'), 'class' => 'required-entry']);

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }
}
