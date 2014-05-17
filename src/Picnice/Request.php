<?php

namespace Thor\Picnice;

class Request {

    /**
     * The HTTP Request Method (verb)
     * @var string
     */
    public $method = 'GET';

    /**
     * Return the protocol string "http" or "https" when requested with TLS
     * @var string
     */
    public $protocol = 'http';

    /**
     * 
     * @var string
     */
    public $serverProtocol = 'HTTP/1.1';

    /**
     * The HTTP hostname (including port if differs from 80)
     * @var string
     */
    public $host;

    /**
     * The HTTP hostname (including port if differs from 80)
     * @var string
     */
    public $port;

    /**
     * Second Level Domain (SLD) of the HTTP hostname
     * @var string 
     */
    public $domain;

    /**
     * Return subdomains as an array (excluding SLD)
     * @var array
     */
    public $subdomains = array();

    /**
     * Base request path
     * @var string
     */
    public $basePath;

    /**
     * The request URL pathname (without the query string nor the filename)
     * 
     * @var string
     */
    public $path;

    /**
     * The request URL filename (without the query string)
     * 
     * @var string
     */
    public $filename;

    /**
     * Path extension (what goes behind the last dot)
     * @var string
     */
    public $extension;

    /**
     * This property is an associative array containing the parsed query-string
     * @var array
     */
    public $query = array();

    /**
     * This property is an associative array containing the parsed request body
     * (POST and PUT variables)
     * @var array
     */
    public $body = array();

    /**
     * 
     * This property is an array containing the files uploaded. It has the same
     * structure as the superglobal $_FILES variable
     * @var array
     */
    public $files = array();

    /**
     * Contains the cookies sent by the user-agent
     * @var array
     */
    public $cookies = array();

    /**
     * This property is an associative array containing the parsed HTTP headers
     * in a Proper-Case naming
     * @var array
     */
    public $headers = array();

    /**
     * Return the remote address
     * @var string
     */
    public $ip;

    /**
     * 
     * @param boolean $resolve Resolve the original request?
     */
    public function __construct($resolve = true) {
        if ($resolve === true) {
            $this->resolve();
        }
    }

    protected function getVar($key, $default = false, $arr = null) {
        if (!is_array($arr)) {
            return isset($_SERVER[$key]) ? $_SERVER[$key] : $default;
        } else {
            return isset($arr[$key]) ? $arr[$key] : $default;
        }
    }

    protected function resolve() {
        $this->method = strtoupper($this->getVar('REQUEST_METHOD', 'GET'));
        $this->protocol = ($this->getVar('HTTP_X_FORWARDED_PROTO') == 'https') ? 'https' : (($this->getVar('HTTPS') != false) ? 'https' : 'http');
        $this->serverProtocol = $this->getVar('SERVER_PROTOCOL', 'HTTP/1.1');
        $this->host = $this->getVar('HTTP_HOST', $this->getVar('SERVER_NAME', 'localhost'));
        $this->port = $this->getVar('SERVER_PORT', 80);
        $this->ip = $this->getVar('REMOTE_ADDR', '::0');
        $this->resolveDomain();
        $this->resolveBasePath();
        $this->resolvePath();
        $this->resolveFilename();
        $this->resolveInput();
        $this->resolveHeaders();
    }

    protected function resolveDomain() {
        // Domain and subdomains
        $this->domain = preg_replace('/\:.+/', '', $this->host);
        $this->subdomains = array();
        $subdomains = explode('.', $this->domain);
        if (count($subdomains) > 2) {
            $this->domain = implode(".", array_slice($subdomains, -2, 2));
            $this->subdomains = array_reverse(array_slice($subdomains, 0, -2));
        }
    }

    protected function resolveBasePath() {
        $script_name = $this->getVar('SCRIPT_NAME', '');

        // Base path
        $this->basePath = '';
        if (!empty($script_name)) {
            $this->basePath = trim(str_replace('\\', '/', dirname($script_name)), '/ ');
        }
    }

    protected function resolvePath() {
        $script_name = $this->getVar('SCRIPT_NAME', '');

        // Path
        $this->path = explode("?", trim($this->getVar('REQUEST_URI', ''), " /"), 2);
        $this->path = $this->path[0];
        if (!empty($this->basePath)) {
            $this->path = preg_replace("/^" . str_replace('/', '\/', $this->basePath) . "/", "", $this->path);
        }

        $this->path = preg_replace("/^" . preg_quote(basename($script_name)) . "\/?/", "", trim($this->path, " /"));
    }

    protected function resolveFilename() {
        // Extension
        $ext = explode(".", $this->path);
        if (count($ext) > 1) {
            $this->extension = array_pop($ext);
            $this->filename = basename($this->path);
            $path = explode('/', $this->path);
            array_pop($path);
            $this->path = implode('/', $path);
        }
    }

    protected function resolveInput() {
        $this->query = $_GET;

        parse_str(file_get_contents("php://input"), $putVars);
        $this->body = array_merge($putVars, $_POST);

        $this->files = $_FILES;
        $this->cookies = $_COOKIE;
    }

    protected function resolveHeaders() {
        //Server headers
        $this->headers = array();
        foreach ($_SERVER as $k => $v) {
            if (preg_match("/^HTTP_/", $k)) {
                $name = ucwords(strtolower(str_replace("_", " ", preg_replace("/^HTTP_/", "", $k))));
                $this->headers[str_replace(" ", "-", $name)] = $v;
            }
        }
    }

    public function hostUrl() {
        return $this->protocol . '://' . $this->host . '/';
    }

    /**
     * Base path URL of current URL
     * @return string
     */
    public function basePathUrl() {
        return rtrim($this->hostUrl() . ltrim($this->basePath, '/'), '/') . '/';
    }

    /**
     * Path URL of current URL, without the filename
     * @return string
     */
    public function pathUrl() {
        return $this->basePathUrl() . $this->path;
    }

    /**
     * Full current (filename) URL
     * @return string
     */
    public function url() {
        return $this->pathUrl() . ($this->filename ? '/' . $this->filename : '');
    }

    /**
     * Current request (relative) URI
     * @return string
     */
    public function uri() {
        return $this->getVar('REQUEST_URI', '');
    }

    /**
     * 
     * @return boolean
     */
    public function isHttps() {
        return $this->protocol == 'https';
    }

    /**
     * Returns a value from the request parameters (query or body, in that order)
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function input($name, $default = false) {
        if (isset($this->query[$name])) {
            return $this->query[$name];
        } elseif (isset($this->body[$name])) {
            return $this->body[$name];
        }
        return $default;
    }

    /**
     * Return the value of header name when present, otherwise return false.
     * @param string|false $name
     * @param mixed $default
     * @return mixed
     */
    public function header($name, $default = false) {
        if (isset($this->headers[$name])) {
            return $this->headers[$name];
        }
        return $default;
    }

}
