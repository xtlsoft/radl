<?php
/**
 * Radl Project
 * 
 * @author Tianle Xu <xtl@xtlsoft.top>
 * @license MIT
 * @package Radl
 */

namespace Radl;

use \Pisp\Pisp;
use \Pisp\StdLib\StandardLibrary;

class Radl {

    public $vm = null;

    public function __construct() {
        $this->vm = new Pisp;
        StandardLibrary::register($this->vm);
    }

    public function generateFromString(string $str): string {
        return $this->vm->execute($str);
    }

    public function generateFromFile(string $f): string {
        return $this->generateFromString(file_get_contents($f));
    }

    public function executeProgram(string $path, string $input): string {

        $descriptorspec = array(
            0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
            1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
            2 => array("pipe", "w")
        );

        $cwd = '/tmp';
        $env = [];

        $process = proc_open($path, $descriptorspec, $pipes, $cwd, $env);

        if (is_resource($process)) {
            fwrite($pipes[0], $input);
            fclose($pipes[0]);
            $rslt = \stream_get_contents($pipes[1]);
            fclose($pipes[1]);
            fclose($pipes[2]);
            $rv = proc_close($process);
            if ($rv !== 0) {
                throw new \Exception("Process exited $rv");
            }
            return $rslt;
        } else {
            throw new \Exception("Process open error");
        }

    }

}

(new Library);