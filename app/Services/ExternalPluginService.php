<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use ZipArchive;
use Exception;

class ExternalPluginService
{
    protected $pluginsPath;
    protected $tempPath;
    protected $maxFileSize = 10485760; // 10MB in bytes

    public function __construct()
    {
        $this->pluginsPath = base_path('modules');
        $this->tempPath = storage_path('app/temp');
    }

    public function getInstalledPlugins()
    {
        try {
            $plugins = [];

            if (!File::exists($this->pluginsPath)) {
                File::makeDirectory($this->pluginsPath, 0755, true);
                return $plugins;
            }

            $directories = File::directories($this->pluginsPath);
            
            foreach ($directories as $directory) {
                $pluginName = basename($directory);
                $pluginJsonPath = $directory . '/module.json';

                if (File::exists($pluginJsonPath)) {
                    $pluginJson = $this->validateAndParseJson($pluginJsonPath);
                    
                    if ($pluginJson && isset($pluginJson['name'])) {
                        $plugins[] = [
                            'name' => $pluginJson['name'],
                            'description' => $pluginJson['description'] ?? '',
                            'version' => $pluginJson['version'] ?? '1.0.0',
                            'author' => $pluginJson['author'] ?? 'Unknown',
                            'enabled' => $this->isPluginEnabled($pluginName),
                            'type' => 'external',
                            'path' => $directory,
                        ];
                    }
                }
            }

            return $plugins;
        } catch (Exception $e) {
            Log::error('Error getting installed plugins: ' . $e->getMessage());
            throw new Exception('Failed to retrieve installed plugins.');
        }
    }

    public function installPlugin($zipFile)
    {
        try {
            $this->validateZipFile($zipFile);
            $this->prepareTempDirectory();

            $zipPath = $this->moveUploadedFile($zipFile);
            $extractedDir = $this->extractZipFile($zipPath);
            $pluginName = basename($extractedDir);

            $this->validatePluginStructure($extractedDir, $pluginName);
            $this->movePluginToModules($extractedDir, $pluginName);
            $this->cleanupTempFiles();

            $this->enablePlugin($pluginName);

            return true;
        } catch (Exception $e) {
            $this->cleanupTempFiles();
            Log::error('Plugin installation failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function uninstallPlugin($name)
    {
        try {
            $pluginPath = $this->pluginsPath . '/' . $name;

            if (!File::exists($pluginPath)) {
                throw new Exception('Plugin not found.');
            }

            $this->disablePlugin($name);
            File::deleteDirectory($pluginPath);

            return true;
        } catch (Exception $e) {
            Log::error('Plugin uninstallation failed: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function validateZipFile($file)
    {
        if ($file->getSize() > $this->maxFileSize) {
            throw new Exception('Plugin package exceeds maximum size limit of 10MB.');
        }

        if ($file->getClientOriginalExtension() !== 'zip') {
            throw new Exception('Invalid file type. Only ZIP files are allowed.');
        }
    }

    protected function prepareTempDirectory()
    {
        if (File::exists($this->tempPath)) {
            File::deleteDirectory($this->tempPath);
        }
        File::makeDirectory($this->tempPath, 0755, true);
    }

    protected function moveUploadedFile($file)
    {
        $zipPath = $this->tempPath . '/' . $file->getClientOriginalName();
        $file->move($this->tempPath, $file->getClientOriginalName());
        return $zipPath;
    }

    protected function extractZipFile($zipPath)
    {
        $zip = new ZipArchive;
        if ($zip->open($zipPath) !== TRUE) {
            throw new Exception('Failed to open ZIP file.');
        }

        $zip->extractTo($this->tempPath);
        $zip->close();

        $directories = File::directories($this->tempPath);
        if (empty($directories)) {
            throw new Exception('Invalid plugin package structure.');
        }

        return $directories[0];
    }

    protected function validatePluginStructure($directory, $pluginName)
    {
        if (!File::exists($directory . '/module.json')) {
            throw new Exception('Invalid plugin package. Missing module.json file.');
        }

        $pluginJson = $this->validateAndParseJson($directory . '/module.json');
        
        if (!$pluginJson || !isset($pluginJson['name'])) {
            throw new Exception('Invalid module.json file.');
        }

        if ($pluginJson['name'] !== $pluginName) {
            throw new Exception('Plugin directory name must match the name in module.json.');
        }
    }

    protected function movePluginToModules($source, $pluginName)
    {
        $destination = $this->pluginsPath . '/' . $pluginName;
        
        if (File::exists($destination)) {
            File::deleteDirectory($destination);
        }
        
        File::moveDirectory($source, $destination);
    }

    protected function cleanupTempFiles()
    {
        if (File::exists($this->tempPath)) {
            File::deleteDirectory($this->tempPath);
        }
    }

    protected function enablePlugin($pluginName)
    {
        Artisan::call('module:enable', ['module' => $pluginName]);
        Artisan::call('module:migrate', ['module' => $pluginName]);
    }

    protected function disablePlugin($pluginName)
    {
        Artisan::call('module:disable', ['module' => $pluginName]);
    }

    protected function validateAndParseJson($filePath)
    {
        $content = File::get($filePath);
        $json = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON format in ' . basename($filePath));
        }

        return $json;
    }

    protected function isPluginEnabled($pluginName)
    {
        $modulesJsonPath = base_path('modules_statuses.json');
        
        if (File::exists($modulesJsonPath)) {
            $modulesJson = $this->validateAndParseJson($modulesJsonPath);
            return isset($modulesJson[$pluginName]) && $modulesJson[$pluginName];
        }

        return false;
    }
} 