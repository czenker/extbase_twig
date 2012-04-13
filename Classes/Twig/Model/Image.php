<?php

class Tx_ExtbaseTwig_Twig_Model_Image {

    public function __construct($src = NULL, $width = NULL, $height = NULL) {
        $this->setSrc($src);
        $this->setWidth($width);
        $this->setHeight($height);
    }

    /**
     * path to the image relative to root dir
     *
     * @var string
     */
    protected $src;

    /**
     * width of the image in px
     *
     * @var integer
     */
    protected $width;

    /**
     * height of the image in px
     *
     * @var integer
     */
    protected $height;

    /**
     * @param int $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param string $src
     */
    public function setSrc($src)
    {
        $this->src = $src;
        return $this;
    }

    /**
     * @return string
     */
    public function getSrc()
    {
        return $this->src;
    }

    /**
     * @param int $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    public function __toString() {
        return $this->getSrc();
    }
}