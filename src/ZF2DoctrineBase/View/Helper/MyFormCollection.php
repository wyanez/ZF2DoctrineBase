<?php
    namespace  ZF2DoctrineBase\View\Helper;

    use Zend\View\Helper\AbstractHelper;
    use Zend\Form\View\Helper\FormCollection;

    use RuntimeException;
    use Zend\Form\Element;
    use Zend\Form\ElementInterface;
    use Zend\Form\Element\Collection as CollectionElement;
    use Zend\Form\FieldsetInterface;
    use Zend\View\Helper\AbstractHelper as BaseAbstractHelper;


    /**
    * MyFormCollection
    * Helper para renderizar las colecciones. 
    * Se modificó el método render para que trabajara con <div> en vez de <fieldset>                
    * @author William Yanez - Agosto 2013       
    */

    class MyFormCollection extends FormCollection{

     
          /**
         * Invoke helper as function
         *
         * Proxies to {@link render()}.
         *
         * @param  ElementInterface|null $element
         * @param  bool                  $wrap
         * @return string|FormCollection
         */
        public function __invoke(ElementInterface $element = null, $wrap = true)
        {
            if (!$element) {
                return $this;
            }

            $this->setShouldWrap($wrap);

            return $this->render($element);
        }
       
         /**
         * Render a collection by iterating through all fieldsets and elements
         *
         * @param  ElementInterface $element
         * @return string
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
            $escapeHtmlHelper = $this->getEscapeHtmlHelper();
            $elementHelper    = $this->getElementHelper();
            $fieldsetHelper   = $this->getFieldsetHelper();

            if ($element instanceof CollectionElement && $element->shouldCreateTemplate()) {
                $templateMarkup = $this->renderTemplate($element);
            }

            foreach ($element->getIterator() as $elementOrFieldset) {
                if ($elementOrFieldset instanceof FieldsetInterface) {
                    $markup .= $fieldsetHelper($elementOrFieldset);
                } elseif ($elementOrFieldset instanceof ElementInterface) {
                    $markup .= $elementHelper($elementOrFieldset);
                }
            }

            // If $templateMarkup is not empty, use it for simplify adding new element in JavaScript
            if (!empty($templateMarkup)) {
                $markup .= $templateMarkup;
            }

            // Every collection is wrapped by a fieldset if needed
            if ($this->shouldWrap) {
                //Esto fue lo que se modificó para que trabajara con <div> en vez de <fieldset> - wyanez                
                $markup = sprintf(
                    '<div>%s</div>',
                    $markup
                );
            }

            return $markup;
        }    
    }
?>