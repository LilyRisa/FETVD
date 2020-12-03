<?php

namespace App\Providers;

class SetEnv
{
    public static function SetEnvValue($envKey, $envValue){
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);
    
        $str .= "\n"; // In case the searched variable is in the last line without \n
        $keyPosition = strpos($str, "{$envKey}=");
        $endOfLinePosition = strpos($str, PHP_EOL, $keyPosition);
        $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
        $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
        $str = substr($str, 0, -1);
    
        $fp = fopen($envFile, 'w');
        $re = fwrite($fp, $str);
        fclose($fp);
        return $re;
    }

}