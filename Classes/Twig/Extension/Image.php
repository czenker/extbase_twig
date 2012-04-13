<?php

class Tx_ExtbaseTwig_Twig_Extension_Image extends Twig_Extension {

    public function getFunctions()
    {
        return array(
            'image' => new Twig_Function_Function('Tx_ExtbaseTwig_Twig_Function_Image::imageFromResource'),
        );
    }



    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'extbasetwig.image';
    }
}