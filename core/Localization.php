<?php
require_once('../config/config.php');
class Localization
{
    private static $lang = DEFAULT_LANGUAGE; 
    private static $translations = [];

    // Set the language
    public static function setLanguage($lang)
    {
        self::$lang = $lang;
        self::loadTranslations();
    }

    // Load translations from the language file
    private static function loadTranslations()
    {
        $basePath = dirname(__DIR__);
        $filePath = $basePath. '/lang/' . self::$lang . '.php'; 
        if (file_exists($filePath)) {
            self::$translations = include $filePath;
        } else {
            self::$translations = []; // Reset to empty if file doesn't exist
        }
    }

    // Get translation by key
    public static function translate($key)
    {
        Localization::setLanguage(DEFAULT_LANGUAGE);
        return self::$translations[$key] ?? $key; // Return key if translation not found
    }
}
