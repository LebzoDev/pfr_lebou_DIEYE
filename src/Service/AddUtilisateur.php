<?php

namespace App\Service;

class AddUtilisateur{
    
    public function transformData($getcontent){
        //Initialiser le tableau de données
        $array = [];
        //Segmenter la chaine
        $data = preg_split("/form-data; /",$getcontent);  
        //Suppression du premier element du tableau
        unset($data[0]);
        foreach ($data as $value) {
            //Enlever les retours chario et retour à la ligne (\r\n)
            $arraySecond = preg_split("/\r\n/", $value);
            //Enlever les deux élèments qui n'en font pas partie (2 dernières)
            array_pop($arraySecond);
            array_pop($arraySecond);
            
            $key = explode ('"',$arraySecond[0]);
            $key= $key[1];
            $array[$key] = end($arraySecond);
        }
        return $array;
       
    }
}