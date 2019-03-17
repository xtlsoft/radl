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
use \Pisp\StdLib\{StandardLibrary, LibraryBase};

class Library extends LibraryBase {

    /**
     * Constructor
     */
    public function __construct() {

        $this->add("repeat", [$this, "repeat"], false);
        $this->add("document", [$this, "document"], false);
        $this->add("ignore", [$this, "ignore"], true);
        $this->add("rand_range", [$this, "randRange"], true);
        $this->add("rand", [$this, "rand"], true);
        $this->functions["endl"] = "\r\n";

    }

    public function repeat($args, \Pisp\VM\VM $vm) {
        $repeat = $vm->runNode($args[0]);
        $rslt = "";
        $args = array_slice($args, 1);
        while (($repeat --) > 0) {
            $rslt .= $this->document($args, $vm);
        }
        return $rslt;
    }

    public function document($args, \Pisp\VM\VM $vm) {
        $rslt = "";
        foreach ($args as $ins) {
            $rslt .= $vm->runNode($ins);
        }
        return $rslt;
    }
    
    public function ignore($args, \Pisp\VM\VM $vm) {
        return "";
    }
    
    public function randRange($args, \Pisp\VM\VM $vm) {
        $range = [];
        foreach ($args as $v) {
            if (is_string($v) && strlen($v) == 2)
                $range = array_merge($range, range(substr($v, 0, 1), substr($v, 1, 1)));
            else if (is_array($v)) $range = array_merge($range, $v);
            else $range[] = $v;
        }
        return "{$range[rand(0, count($range)-1)]}";
    }

    public function rand($args, \Pisp\VM\VM $vm) {
        if (count($args) == 0) return (string) rand(-2147483648, 2147483647);
        else if (count($args) == 1) return (string) rand(0, $args[0]);
        else if (count($args) == 2) return (string) rand($args[0], $args[1]);
        else throw new \Pisp\Exceptions\RuntimeException("Error in rand: Invalid parameter count.");
    }

}

StandardLibrary::add(Library::class);
