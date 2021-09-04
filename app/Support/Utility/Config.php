<?php

namespace Support\Utility;

class Config
{
    private static $config = [];

    /**
     * @param string $key
     * @return mixed
     */
    public static function get($key)
    {
        list($file, $key) = array_pad(
            explode('.', strtolower($key)), 2, null
        );
        self::check($file);
        if (isset($key)) {
            return self::$config[$file][$key] ?? null;
        }
        return self::$config[$file] ?? [];
    }

    /**
     * @param string $file
     * @return void
     */
    private static function check($file)
    {
        if (! isset(self::$config[$file])) {
            self::$config[$file] = self::load($file);
        }
    }

    /**
     * @param string $file
     * @return array
     */
    private static function load($file)
    {
        $file = sprintf('%s/config/%s.php', ROOT, $file);
        if (! file_exists($file)) {
            return [];
        }
        return require($file);
    }
}
?>