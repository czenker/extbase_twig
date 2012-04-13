<?php

class Tx_ExtbaseTwig_Twig_Function_Image {

    /**
     * @static
     * @param Tx_ExtbaseTwig_Twig_Environment $env
     * @param $src
     * @param $width
     * @param $height
     * @return mixed
     * @throws Tx_Fluid_Core_ViewHelper_Exception
     */
    public static function imageFromResource($src, $width, $height) {
        if(TYPO3_MODE === 'BE') {
            throw new RuntimeException(get_class(self).' currently only works in Frontend.');
        }

        $setup = array(
            'width' => $width,
            'height' => $height,
        );

        $imageInfo = $GLOBALS['TSFE']->cObj->getImgResource($src, $setup);
        $GLOBALS['TSFE']->lastImageInfo = $imageInfo;
        if (!is_array($imageInfo)) {
            throw new InvalidArgumentException('Could not get image resource for "' . htmlspecialchars($src) . '".' , 1253191060);
        }
        $imageInfo[3] = t3lib_div::png_to_gif_by_imagemagick($imageInfo[3]);

        $GLOBALS['TSFE']->imagesOnPage[] = $imageInfo[3];

        $imageSource = $GLOBALS['TSFE']->absRefPrefix . t3lib_div::rawUrlEncodeFP($imageInfo[3]);

        return $imageSource;

    }

}