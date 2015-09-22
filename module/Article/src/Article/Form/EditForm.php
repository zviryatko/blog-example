<?php
/**
 * @file
 *
 */

namespace Article\Form;


use Zend\Form\Fieldset;

class EditForm extends AddForm
{
    public function init()
    {
        parent::init();

        $this->get('actions')
            ->get('submit')
            ->setAttribute('value', $this->translate('Update'))
            ->setOption('wrapper_class', 'col-sm-offset-0')
        ;
        $this->get('actions')->add(
            array(
                'name' => 'delete',
                'type' => 'submit',
                'options' => array(
                    'wrapper_class' => 'col-sm-offset-3',
                ),
                'attributes' => array(
                    'value' => $this->translate('Delete'),
                    'class' => 'btn btn-danger btn-lg',
                ),
            )
        );
    }
}