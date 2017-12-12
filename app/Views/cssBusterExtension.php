<?php
namespace App\Views;

class cssBusterExtension extends \Twig_Extension
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
            new \Twig_SimpleFunction('cssBundle', array($this, 'cssBundle'))
        ];
    }

    public function cssBundle()
    {
        if($this->buster){
            return $this->buster[1]['file'] . '?v='. $this->buster[1]['hash'];
        }else{
            return false;
        }
    }
}