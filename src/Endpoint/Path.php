<?php namespace Endpoint;

class Path {
    protected static
        $config_dir,
        $base_dir,
        $lib_dir;

    public static function setConfigDir($directory) {
        self::$config_dir = $directory;
    }

    public static function setBaseDir($directory) {
        self::$base_dir = $directory;
    }

    public static function setLibDir($directory) {
        self::$lib_dir = $directory;
    }

    public static function getConfigDir() {
        return self::$config_dir;
    }

    public static function getBaseDir() {
        return self::$base_dir;
    }

    public static function getLibDir() {
        return self::$lib_dir;
    }
}
