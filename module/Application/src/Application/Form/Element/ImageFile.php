<?php
/**
 * @file
 *
 */

namespace Application\Form\Element;


use Zend\Form\Element\File;

class ImageFile extends File
{
    protected $attributes = array(
        'type' => 'imagefile',
    );

    public function resetOriginalType()
    {
        $this->attributes['type'] = 'file';
    }
}