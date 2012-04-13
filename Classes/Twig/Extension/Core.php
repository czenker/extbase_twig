<?php

class Tx_ExtbaseTwig_Twig_Extension_Core extends Twig_Extension {

    public function getTokenParsers() {
        return array(
            // override import to use the Partials folder
            new Tx_ExtbaseTwig_Twig_TokenParser_Import(),
            // override include to use the Partials folder
            new Tx_ExtbaseTwig_Twig_TokenParser_Include(),
        );
    }




    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'extbasetwig.core';
    }
}