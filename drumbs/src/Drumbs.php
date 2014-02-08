<?php

class Drumbs extends \Mjolnic\Resthin\Server {

    const REGEX_DEFAULT = '/.*\/(\d+|A|N)x(\d+|A|N)x(C|R)\/(.*)\.(png|jpg|jpeg)$/';
    const REGEX_FILTERS = '/.*\/(\d+|A|N)x(\d+|A|N)x[A-Z0-9]{1,2}\/(.*)\.(png|jpg|jpeg)$/';

    public $data = array();
    public $config = array();
    protected static $instance;

    public function __construct($config = array(), \Mjolnic\Resthin\Router $router = null, \Mjolnic\Resthin\Request $request = null, \Mjolnic\Resthin\Response $response = null) {
        $this->config = $config;
        parent::__construct($router, $request, $response);
        if (empty(static::$instance)) {
            static::$instance = $this;
        }
    }

    /**
     * 
     * @return Drumbs
     */
    public static function getInstance() {
        if (static::$instance == null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function path($to = null) {
        if (empty($to)) {
            return $this->config['paths']['root'];
        } elseif (isset($this->config['paths'][$to])) {
            return $this->config['paths'][$to];
        } else {
            return $this->config['paths']['root'] . ltrim($to, '/\\');
        }
    }

    public function parse($uri) {
        try {
            $def = explode('/', $uri);
            $filename = array_pop($def);
            $foldername = array_pop($def);
            $params = explode('x', $foldername);
            if (count($params) != 3) {
                return false;
            }

            $parentpath = $this->path() . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $def) . DIRECTORY_SEPARATOR;

            if (!preg_match($this->config['allowed_paths'], $parentpath)) {
                return false;
            }
            $data = array('filename' => $filename, 'foldername' => $foldername, 'params' => $params, 'parentpath' => $parentpath, 'uri' => $uri);
            $data['original'] = $parentpath . $data['filename'];
            $data['destination'] = $parentpath . $foldername . DIRECTORY_SEPARATOR . $data['filename'];

            if (!is_readable($data['original']) or !is_writable($data['parentpath'])) {
                return false;
            }

            // Allowed format?
            if (!in_array($data['foldername'], $this->config['allowed_formats'])) {
                return false;
            }

            $filternames = array_keys($this->config['filters']);
            $filternames[] = 'C'; //crop
            $filternames[] = 'R'; //resize

            if (!in_array($data['params'][2], $filternames)) {
                return false;
            }
            return $data;
        } catch (Exception $exc) {
            echo $exc->getMessage();
            return false;
        }
    }

    public function process($uri) {
        $data = $this->parse($uri);
        if ($data != false) {
            $destpath = $data['parentpath'] . $data['foldername'] . DIRECTORY_SEPARATOR;
            if (!is_dir($destpath)) {
                mkdir($destpath, 0775, true);
            }

            $img = WideImage\WideImage::load($data['original']);
            if ($data['params'][2] == 'C') {
                // COVER (CROP + FILL + CENTER)
                $img->resize($data['params'][0], $data['params'][1], 'outside')
                        ->crop('center', 'center', $data['params'][0], $data['params'][1])
                        ->saveToFile($data['destination']);
            } elseif ($data['params'][2] == 'R') {
                // FIT INSIDE (RESIZE)
                $img->resize($data['params'][0], $data['params'][1], 'inside')
                        ->saveToFile($data['destination']);
            } else {
                // OTHER FILTERS
                $filter = $this->config['filters'][$data['params'][2]];
                if (!is_callable($filter)) {
                    return false;
                } else {
                    $filter($img, $data);
                }
            }
            if ($this->request()->get('r') != 'n') { // reload once
                $this->response()->redirect($this->request()->baseUrl() . $uri . '?r=n');
            }
        } else {
            $this->response()->send404();
        }
    }

}
