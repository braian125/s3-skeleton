<?php
namespace App\Views;

class jsBusterExtension extends \Twig_Extension
{
    public $busterPath;
    public $buster;
    
    public function __construct($busterPath)
    {
        $this->busterPath = $busterPath;

        if(file_exists( __DIR__ . $this->busterPath )){
            $busters = file_get_contents( __DIR__ . $this->busterPath );
            $busters = json_decode($busters, true);
            foreach ($busters as $key => $value) {
                $_busters[] = [
                    "file" => $key,
                    "hash" => $value
                ];
            }
            $this->buster = $_busters;
        }else{
            $this->buster = false;
        }
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('jsBundle', array($this, 'jsBundle'))
        ];
    }

    public function jsBundle()
    {
        if($this->buster){
            return $this->buster[0]['file'] . '?v='. $this->buster[0]['hash'];
        }else{
            return false;
        }
    }
}