<?php

use Database\TinyMVCDatabase as DB;

class Proxy extends Database\TinyMVCDatabaseObject{
    const tablename  = 'proxy';
    
    public function getFresh(){
        $query = DB::createQuery()->select('proxy')->where("timestamp > ?"); 
        $query_response = self::getList($query, [time()-1800]);
        $max = count($query_response)-1;
        $proxy = $query_response[rand(0, $max)];
        
        return $proxy->proxy;
    }
    
}
