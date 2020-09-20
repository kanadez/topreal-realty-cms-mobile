<?php

class Log{
    public static function i($tag, $message){
        $file_path = '/home/admin/web/dev.topreal.top/public_html/logs/i.txt';
        $content_before = file_get_contents($file_path);
        file_put_contents($file_path, date('Y/m/d H:i:s')." ".$tag.": ".$message.PHP_EOL.$content_before);
    }
}