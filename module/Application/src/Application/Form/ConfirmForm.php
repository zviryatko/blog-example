<?php
/**
 * @file
 *
 */

namespace Application\Form;


class ConfirmForm extends AbstractForm
{
    public function init()
    {
        $this->setAttribute('class', 'form-inline');

        $this->add(
            array(
                'name' => 'confirm',
                'type' => 'submit',
                'attributes' => array(
                    'value' => $this->getOption('confirm'),
                    'class' => 'btn btn-danger btn-lg',
                ),
            )
        );

        $this->add(
            array(
                'name' => 'decline',
                'type' => 'submit',
                'attributes' => array(
                    'value' => $this->getOption('decline'),
                    'class' => 'btn btn-link btn-lg',
                ),
            )
        );
    }
}