<?php

namespace Digima;

class AutoLoader
{
    /**
     * @var string
     */
    private $rootDirectory;

    /**
     * @param string $rootDirectory
     * @return void
     */
    public function __construct($rootDirectory)
    {
        $this->rootDirectory = $rootDirectory;
    }

    /**
     * @return void
     */
    public function load()
    {
        $composer = json_decode(file_get_contents($this->rootDirectory."/composer.json"), true);

        if (isset($composer["autoload"]["psr-4"])) {
            $this->loadPsr4($composer['autoload']['psr-4']);
        }

        if (isset($composer["autoload"]["psr-0"])) {
            $this->loadPsr0($composer['autoload']['psr-0']);
        }

        if (isset($composer["autoload"]["files"])) {
            $this->loadFiles($composer["autoload"]["files"]);
        }
    }

    /**
     * @param array $files
     * @return void
     */
    public function loadFiles(array $files)
    {
        foreach ($files as $file) {
            $fullPath = $this->rootDirectory."/".$file;

            if (! file_exists($fullPath)) {
                continue;
            }

            include_once($fullPath);
        }
    }

    /**
     * @param array $namespaces
     * @param bool $isPsr4
     * @return void
     */
    public function loadPsr(array $namespaces, $isPsr4)
    {
        $dir = $this->rootDirectory;

        // For each namespace specified in the Composer config, load the associated classes
        foreach ($namespaces as $namespace => $classPaths) {
            $this->loadClass($isPsr4, $classPaths, $namespace, $dir);
        }
    }

    /**
     * @param array $namespaces
     * @return void
     */
    public function loadPsr0(array $namespaces)
    {
        $this->loadPsr($namespaces, false);
    }

    /**
     * @param array $namespaces
     * @return void
     */
    public function loadPsr4(array $namespaces)
    {
        $this->loadPsr($namespaces, true);
    }

    /**
     * @param bool $isPsr4
     * @param string $classPaths
     * @param string $namespace
     * @param string $directory
     */
    private function loadClass($isPsr4, $classPaths, $namespace, $directory)
    {
        if (! is_array($classPaths)) {
            $classPaths = array($classPaths);
        }

        $rootDirectory = $this->rootDirectory;

        spl_autoload_register(function ($classname) use ($namespace, $classPaths, $directory, $isPsr4, $rootDirectory) {
            // Check if the namespace matches the class we are looking for
            if (! preg_match("#^".preg_quote($namespace)."#", $classname)) {
                return;
            }

            // Remove the namespace from the file path since it's psr4
            if ($isPsr4) {
                $classname = str_replace($namespace, "", $classname);
            }

            $fileName = preg_replace("#\\\\#", "/", $classname).".php";

            foreach ($classPaths as $classPath) {
                $fullPath = $rootDirectory."/".$classPath."/$fileName";

                if (file_exists($fullPath)) {
                    include_once $fullPath;
                }
            }
        });
    }
}
