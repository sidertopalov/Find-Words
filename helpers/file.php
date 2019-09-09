<?php

class File
{
    private $file;
    private $handle;
    private $fileContent;
    
    public function __construct($source = null) {
        if($source !== null) {
            $this->load($source);
        }
    }

    public function load($source) {
        $this->handle = fopen($source, 'c+');

        if (!$this->handle) {
            throw new Exception("Error proccessing file load");
        }
        
        $this->file = $source;
        $this->fileContent = file_get_contents($source);

        return $this;
    }

    public function getFile($file = null) {
        return $file === null ? $this->file : $file;
    }

    public function getSize() {
        return filesize($this->file);
    }
    
    public function getFileContent() {
        return file_get_contents($this->file);
    }

    /**
    * @return Generator
    */
    public function read() {
        while(($line = fgets($this->handle)) !== false) {
            yield $line;
        }
    }

    public function write($text, $append = false) {
        if($append) {
            file_put_contents($this->file, $text . PHP_EOL , FILE_APPEND | LOCK_EX);
        } else {
            file_put_contents($this->file, $text . PHP_EOL, LOCK_EX);
        }
    }

    public static function delete($file = null) {
        $temp = $this->getFile($file);
        if(file_exist($temp)) {
            return unlink($temp) ? true : false;
        }
        return false;
    }

    /**
    * @return boolean
    */
    public function isExist($needle, $haystack = null) {
        $temp = $temp = $this->getFile($haystack);
        $fileContent = file_get_contents($temp);

        return strpos($fileContent, $needle) !== false;
    }
}

?>