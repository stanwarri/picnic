<?php

namespace Mjolnic\Picnic;

class Server extends Request {

    /**
     *
     * @var array
     */
    public $config = array();

    public function __construct(array $config) {
        $this->config = $config;
        parent::__construct(true);
    }

    public function process(Task $task = null) {
        if (empty($task)) {
            $task = new Task($this->path, $this->filename, $this->extension, $this->config['public_path']);
        }

        // Can we read the original file and write in the original path?
        if (!$task->isValid()) {
            return $this->halt();
        }

        // Can we operate in this path?
        if (!preg_match($this->config['path_mask'], trim(str_replace($this->config['public_path'], '', $task->origPath), DIRECTORY_SEPARATOR))) {
            return $this->halt();
        }

        // Is a valid task name and can we call the action?
        $callable = $this->getActionCallable($task->name);
        if ($callable === false) {
            return $this->halt();
        } else {
            $task->image = \WideImage\WideImage::load($task->origFile);
            // Create destination path
            if (!file_exists($task->destPath) and !is_dir($task->destPath)) {
                @mkdir($task->destPath, 0775, true);
            }
            if (call_user_func($callable, $task) !== false) { // If the action does not return false explicitly...
                $this->reload(); // SUCCESS exit point: reload the image URL
            }
        }
        $this->halt();
    }

    public function getActionCallable($taskname) {
        foreach ($this->config['allowed_tasks'] as $regex => $callable) {
            if (preg_match('/^' . $regex . '$/', $taskname)) {
                return is_callable($callable) ? $callable : false;
            }
        }
        return false;
    }

    public function halt() {
        $this->sendStatus('404 Not Found');
        exit();
    }

    public function reload() {
        if ($this->input('r') != 'n') { // reload only once
            $this->redirect($this->url() . '?r=n');
        } else {
            $this->redirect($this->url());
        }
    }

    /**
     * Sends the redirection headers and ends the script execution
     * @param string $url
     * @param string $status
     */
    public function redirect($url, $status = '302 Found') {
        $this->sendStatus($status);
        header("Location: " . $url);
        exit();
    }

    public function sendStatus($status) {
        if (strpos(strtolower(php_sapi_name()), 'cgi') !== false) {
            header("Status: " . $status);
        } else {
            header($this->serverProtocol . " " . $status);
        }
    }

}
