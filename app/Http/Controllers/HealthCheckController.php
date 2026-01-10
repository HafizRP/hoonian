<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Pusher\Pusher;

class HealthCheckController extends Controller
{
    public function index()
    {
        // 1. Check Database
        $dbStatus = 'unhealthy';
        $dbMessage = '';
        $dbLatency = 0;

        try {
            $startTime = microtime(true);
            DB::connection()->getPdo();
            $dbLatency = round((microtime(true) - $startTime) * 1000, 2);
            $dbStatus = 'healthy';
            $dbMessage = 'Connected successfully';
        } catch (\Exception $e) {
            $dbStatus = 'unhealthy';
            $dbMessage = $e->getMessage();
        }

        // 2. Check Pusher
        $pusherStatus = 'unhealthy';
        $pusherMessage = '';
        $pusherLatency = 0;

        try {
            $config = config('broadcasting.connections.pusher');
            if (!$config) {
                throw new \Exception('Pusher configuration not found');
            }

            if (!class_exists('Pusher\Pusher')) {
                $pusherStatus = 'warning';
                $pusherMessage = 'Pusher PHP SDK not installed. Config present.';
            } else {
                $pusher = new Pusher(
                    $config['key'],
                    $config['secret'],
                    $config['app_id'],
                    $config['options'] ?? []
                );

                // Using get('/channels') as a ping
                $startTime = microtime(true);
                $response = $pusher->get('/channels');
                $pusherLatency = round((microtime(true) - $startTime) * 1000, 2);

                if ($response && isset($response->channels)) {
                    $pusherStatus = 'healthy';
                    $pusherMessage = 'Connected to Pusher API';
                } else {
                    $pusherStatus = 'warning';
                    $pusherMessage = 'Connected but unexpected response';
                }
            }

        } catch (\Exception $e) {
            $pusherStatus = 'unhealthy';
            $pusherMessage = 'Connection failed: ' . $e->getMessage();
        }

        // 3. Web Service (Self)
        $webStatus = 'healthy';
        $webMessage = 'Running';

        $lastChecked = now();

        return view('health.index', compact(
            'dbStatus',
            'dbMessage',
            'dbLatency',
            'pusherStatus',
            'pusherMessage',
            'pusherLatency',
            'webStatus',
            'webMessage',
            'lastChecked'
        ));
    }
}
