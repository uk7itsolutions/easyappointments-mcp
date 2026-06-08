<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;

class InstallerController extends Controller
{
    public function show()
    {
        return view('installer.index');
    }

    public function install(Request $request)
    {
        $request->validate([
            'ea_base_url' => ['required', 'url'],
            'ea_api_key'  => ['required', 'string'],
        ]);

        $baseUrl = rtrim($request->ea_base_url, '/');

        // Verify the API key works against the EA instance
        $check = Http::withToken($request->ea_api_key)
            ->acceptJson()
            ->timeout(10)
            ->get("{$baseUrl}/api/v1/settings");

        if ($check->failed()) {
            return back()->withErrors([
                'ea_api_key' => 'Could not connect to EasyAppointments. Check the URL and API key.',
            ])->withInput();
        }

        // Write the .env file
        $this->writeEnv([
            'APP_URL'     => $request->getSchemeAndHttpHost(),
            'EA_BASE_URL' => $baseUrl,
            'EA_API_KEY'  => $request->ea_api_key,
        ]);

        // Generate app key if not already set
        if (empty(config('app.key'))) {
            Artisan::call('key:generate', ['--force' => true]);
        }

        // Write the installed lock file
        file_put_contents(storage_path('installed'), date('Y-m-d H:i:s'));

        return redirect()->route('installer.complete');
    }

    public function complete()
    {
        return view('installer.complete');
    }

    private function writeEnv(array $values): void
    {
        $envPath = base_path('.env');

        if (!file_exists($envPath)) {
            copy(base_path('.env.example'), $envPath);
        }

        $env = file_get_contents($envPath);

        foreach ($values as $key => $value) {
            $escaped = str_contains($value, ' ') ? "\"{$value}\"" : $value;

            if (preg_match("/^{$key}=/m", $env)) {
                $env = preg_replace("/^{$key}=.*/m", "{$key}={$escaped}", $env);
            } else {
                $env .= "\n{$key}={$escaped}";
            }
        }

        file_put_contents($envPath, $env);
    }
}
