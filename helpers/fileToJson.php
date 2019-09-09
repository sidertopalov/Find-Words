<?php

class ConvertTxtToJsonFormat
{
    private $source_file;
    private $destination_file;
    private $fp;

    public function __construct($source_file, $destination_file) {
        $this->source_file = $source_file;
        $this->destination_file = $destination_file;
    }

    /**
    * @return Generator
    */
    private function read() {
        try {
            $fp = fopen($this->source_file, 'r');

            while(($line = fgets($fp)) !== false) {
                yield rtrim($line, PHP_EOL);
            }
    
            fclose($fp);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
    private function append($data) {
        try {
            $this->fp = fopen($this->destination_file, 'a');
            fwrite($this->fp, $data);
            fclose($this->fp);
        } catch (\Exception $e) {
            echo $e->getMessage();   
        }
    }

    private function writeJson($data) {
        try {
            $this->fp = fopen($this->destination_file, 'w');
            fwrite($this->fp, json_encode($data));
            fclose($this->fp);
        } catch (\Exception $e) {
            echo $e->getMessage();   
        }
    }

    public function convert($customValidationCb = null) {
        
        $count = 0;
        $sleepAt = 50000;
        $jsonString = '{'.PHP_EOL;
        $this->append('{'.PHP_EOL);
        foreach ($this->read() as $line) {
            if ($customValidationCb !== null && is_callable($customValidationCb)) {
                $line = call_user_func_array($customValidationCb, array($line));
                if(!call_user_func_array($customValidationCb, array($line))) {
                    continue;
                }
            }
            if($this->read()->valid()) {
                $jsonString = "\t\"{$line}\": \"{$line}\"," . PHP_EOL;
            } else {
                $jsonString = "\t\"{$line}\": \"{$line}\"" . PHP_EOL;
            }
            
            $this->append($jsonString);
            if ($count > $sleepAt) {
                echo "${count} words inserted <br/>";
                $sleepAt = $sleepAt + 50000;
                sleep(1);
            }
            $count++;
            
        }
        $this->append('}');

        echo '<h3> DONE </h3>';
    }

}

$source = '../bg_BG_new.txt';
$destination = '../bg_BG_new.json';

$convertClass = new ConvertTxtToJsonFormat($source, $destination);
if(isset($_GET['convert']) && $_GET['convert']) {
    $convertClass->convert(function($value) {
        if(!$value) {
            return false;
        }
        $value = trim($value);
        if(preg_match( '/\d/', $value ) > 0 || strpos($value, ' ') !== false || mb_strlen($value) <= 2) {
            return false;
        }
    
        $data = explode('/', mb_strtolower($value));
        return $data[0];
    });
}

echo '<a href="?convert=1"> <h3>Convert File "' . $source . '" to "' . $destination . '"</h3></a>'

?>