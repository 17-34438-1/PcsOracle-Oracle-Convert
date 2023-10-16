<?php
class UtilModel extends CI_Model
{
   
   public function __construct() {
       parent::__construct();

   }

   function FormatBLNo($bl){
        $nbr='';
        if(strpos($bl, '_') !== false){
            $words = explode('_', $bl);
            $nbr=$words[1];
        } else{
            $nbr=$bl;
        }
        return $nbr;
   }

}