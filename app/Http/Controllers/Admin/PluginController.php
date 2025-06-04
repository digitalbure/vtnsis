<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ExternalPluginService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PluginController extends Controller
{
    protected $pluginService;

    public function __construct(ExternalPluginService $pluginService)
    {
        $this->pluginService = $pluginService;
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        try {
            $plugins = $this->pluginService->getInstalledPlugins();
            return view('admin.plugins.index', compact('plugins'));
        } catch (\Exception $e) {
            Log::error('Error displaying plugins: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load plugins. Please try again.');
        }
    }

    public function create()
    {
        return view('admin.plugins.upload');
    }

    public function store(Request $request)
    {
        $request->validate([
            'plugin' => [
                'required',
                'file',
                'mimes:zip',
                'max:10240', // 10MB
            ],
        ], [
            'plugin.required' => 'Please select a plugin package to upload.',
            'plugin.file' => 'The uploaded file is invalid.',
            'plugin.mimes' => 'The plugin package must be a ZIP file.',
            'plugin.max' => 'The plugin package must not exceed 10MB.',
        ]);

        try {
            $this->pluginService->installPlugin($request->file('plugin'));
            return redirect()->route('admin.plugins.index')
                ->with('success', 'Plugin installed successfully.');
        } catch (\Exception $e) {
            Log::error('Plugin installation failed: ' . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($name)
    {
        try {
            $this->pluginService->uninstallPlugin($name);
            return redirect()->route('admin.plugins.index')
                ->with('success', 'Plugin uninstalled successfully.');
        } catch (\Exception $e) {
            Log::error('Plugin uninstallation failed: ' . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }
} 