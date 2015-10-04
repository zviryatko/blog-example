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
        ;
        $this->get('actions')->add(
            array(
                'name' => 'delete',
                'type' => 'submit',
                'options' => array(
                    'disable_group_wrapper' => true,
                ),
                'attributes' => array(
                    'value' => $this->translate('Delete'),
                    'class' => 'btn btn-danger',
                ),
            )
        );
    }
}