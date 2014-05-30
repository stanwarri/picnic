<?php

namespace Mjolnic\Picnic;

class Task {

    /**
     *
     * @var string
     */
    public $publicPath = false;

    /**
     * Task name (thumb folder name)
     * @var string 
     */
    public $name;

    /**
     *
     * @var string 
     */
    public $params = array();

    /**
     *
     * @var string 
     */
    public $prefix;

    /**
     *
     * @var string 
     */
    public $filename;

    /**
     *
     * @var string 
     */
    public $extension;

    /**
     * Full origin file path
     * @var string 
     */
    public $origPath;

    /**
     * Full origin file path
     * @var string 
     */
    public $origFile;

    /**
     * Destination folder name (not path)
     * @var string 
     */
    public $destPath;

    /**
     * Full destination file path
     * @var string 
     */
    public $destFile;

    /**
     *
     * @var \WideImage\Image
     */
    public $image;

    public function __construct($path, $filename, $extension, $publicPath) {
        $this->publicPath = $publicPath;
        $path = explode('/', $path);
        $this->name = array_pop($path);

        $params = explode('_', $this->name);
        $this->prefix = array_shift($params); //remove the folder task from the params
        $this->params = $params;

        $this->filename = $filename;
        $this->extension = $extension;
        $this->origPath = $publicPath . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $path) . DIRECTORY_SEPARATOR;
        $this->origFile = $this->origPath . $this->filename;
        $this->destPath = $this->origPath . $this->name . DIRECTORY_SEPARATOR;
        $this->destFile = $this->destPath . $this->filename;
    }

    public function isValid() {
        return is_readable($this->origFile) and is_writable($this->origPath);
    }

}
