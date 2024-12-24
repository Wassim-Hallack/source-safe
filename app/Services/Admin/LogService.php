<?php

namespace App\Services\Admin;

class LogService
{
    private function check_if_file_exists($filePath): void
    {
        if (!file_exists($filePath)) {
            response()->json([
                'status' => false,
                'message' => 'Log file not found.',
            ], 404);
        }
    }

    public function get_log($request)
    {
        if ($request['log_file_name'] === 'RequestFlow') {
            $filePath = storage_path("logs/RequestFlow.log");
            $this->check_if_file_exists($filePath);

            return response()->file($filePath);
        }

        return response()->json([
            'status' => false,
            'response' => "The log file name incorrect."
        ]);
    }
}
