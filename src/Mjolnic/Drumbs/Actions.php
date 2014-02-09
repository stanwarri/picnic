<?php

namespace Mjolnic\Drumbs;

/**
 * Predefined actions
 */
class Actions {

    public static function grayscale(Task $task) {
        $task->image->asGrayscale()->saveToFile($task->destFile);
    }

    public static function resizeInside(Task $task) {
        $task->params = self::parseDimensions($task->params);
        if ($task->params == false) {
            return false;
        }
        $task->image->resize($task->params[0], $task->params[1], 'inside')
                ->saveToFile($task->destFile);
    }

    public static function resizeOutside(Task $task) {
        $task->params = self::parseDimensions($task->params);
        if ($task->params == false) {
            return false;
        }
        $task->image->resize($task->params[0], $task->params[1], 'outside')
                ->saveToFile($task->destFile);
    }

    public static function resizeContainCentered(Task $task) {
        $task->params = self::parseDimensions($task->params);
        if ($task->params == false) {
            return false;
        }
        if (count($task->params) < 3) {
            return false;
        }
        $task->image->resize($task->params[0], $task->params[1], 'inside')
                ->resizeCanvas($task->params[0], $task->params[1], 'center', 'center', hexdec($task->params[2]))
                ->saveToFile($task->destFile);
    }

    /**
     * Cover: Resize, center and crop
     * @param \Mjolnic\Drumbs\Task $task
     * @return boolean
     */
    public static function resizeCoverCentered(Task $task) {
        $task->params = self::parseDimensions($task->params);
        if ($task->params == false) {
            return false;
        }
        $task->image->resize($task->params[0], $task->params[1], 'outside')
                ->crop('center', 'center', $task->params[0], $task->params[1])
                ->saveToFile($task->destFile);
    }

    public static function autoCrop(Task $task) {
        if (count($task->params) < 1) {
            return false;
        }
        $task->image->autoCrop($task->params[0])
                ->saveToFile($task->destFile);
    }

    protected static function parseDimensions(array $params) {
        if (count($params) < 2) {
            return false;
        }
        if (!is_numeric($params[0]) or !is_numeric($params[1])) {
            return false;
        }
        if ($params[0] == 0) {
            $params[0] = null; // null means auto
        } else {
            $params[0] = intval($params[0]);
        }
        if ($params[1] == 0) {
            $params[1] = null; // null means auto
        } else {
            $params[1] = intval($params[1]);
        }

        return $params;
    }

}
