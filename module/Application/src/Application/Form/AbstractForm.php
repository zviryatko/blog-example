<?php
/**
 * @file
 *
 */

namespace Application\Form;

use Zend\Form\Form;
use Zend\I18n\Translator\TranslatorAwareInterface;
use Zend\I18n\Translator\TranslatorAwareTrait;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\InputFilter\InputFilterProviderInterface;

class AbstractForm extends Form implements TranslatorAwareInterface, TranslatorInterface
{
    use TranslatorAwareTrait;

    /**
     * @inheritdoc
     */
    public function translate($message, $textDomain = 'default', $locale = null)
    {
        if ($this->hasTranslator()) {
            return $this->getTranslator()->translate($message, $textDomain, $locale);
        }

        return $message;
    }

    /**
     * @inheritdoc
     */
    public function translatePlural(
        $singular,
        $plural,
        $number,
        $textDomain = 'default',
        $locale = null
    ) {
        if ($this->hasTranslator()) {
            return $this->getTranslator()->translatePlural(
                $singular,
                $plural,
                $number,
                $textDomain,
                $locale
            );
        }

        return $singular;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->add(
            array(
                'name' => 'csrf',
                'type' => 'csrf',
                'required' => true,
                'validators' => array(
                    array('name' => 'csrf'),
                ),
            )
        );
    }
}