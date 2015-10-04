<?php
/**
 * @file
 *
 */

namespace Application\View\Helper;

use Zend\Form\Element;
use Zend\Form\ElementInterface;
use Zend\Form\Element\Collection as CollectionElement;
use Zend\Form\FieldsetInterface;
use Zend\Form\LabelAwareInterface;
use Zend\View\Helper\HelperInterface;
use Zend\Form\View\Helper\FormCollection as BaseFormCollection;

class FormCollection extends BaseFormCollection
{
    /**
     * @inheritdoc
     */
    public function render(ElementInterface $element)
    {
        $renderer = $this->getView();
        if (!method_exists($renderer, 'plugin')) {
            // Bail early if renderer is not pluggable
            return '';
        }

        $markup           = '';
        $templateMarkup   = '';
        $elementHelper    = $this->getElementHelper();
        $fieldsetHelper   = $this->getFieldsetHelper();
        $wrapperClass     = $element->getOption('wrapper_class');

        if ($element instanceof CollectionElement && $element->shouldCreateTemplate()) {
            $templateMarkup = $this->renderTemplate($element);
        }

        foreach ($element->getIterator() as $elementOrFieldset) {
            if ($elementOrFieldset instanceof FieldsetInterface) {
                $markup .= $fieldsetHelper($elementOrFieldset, $this->shouldWrap());
            } elseif ($elementOrFieldset instanceof ElementInterface && is_callable($elementHelper)) {
                $markup .= $elementHelper($elementOrFieldset);
            }
        }

        if ($wrapperClass) {
            $markup = "<div class='{$wrapperClass}'>{$markup}</div>";
        }

        // Every collection is wrapped by a fieldset if needed
        if ($this->shouldWrap) {
            $attributes = $element->getAttributes();
            unset($attributes['name']);
            $attributesString = count($attributes) ? ' ' . $this->createAttributesString($attributes) : '';

            $label = $element->getLabel();
            $legend = '';

            if (!empty($label)) {
                if (null !== ($translator = $this->getTranslator())) {
                    $label = $translator->translate(
                        $label,
                        $this->getTranslatorTextDomain()
                    );
                }

                if (! $element instanceof LabelAwareInterface || ! $element->getLabelOption('disable_html_escape')) {
                    $escapeHtmlHelper = $this->getEscapeHtmlHelper();
                    $label = $escapeHtmlHelper($label);
                }

                $legend = sprintf(
                    $this->labelWrapper,
                    $label
                );
            }

            $markup = sprintf(
                $this->wrapper,
                $markup,
                $legend,
                $templateMarkup,
                $attributesString
            );
        } else {
            $markup .= $templateMarkup;
        }

        return $markup;
    }
}