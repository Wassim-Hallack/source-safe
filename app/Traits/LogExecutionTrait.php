<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait LogExecutionTrait
{
    public function logExecution(callable $function, string $functionName, array $params = [])
    {
        // Log before execution
        Log::info("Executing function: $functionName", ['params' => $params]);

        // Call the actual function
        $result = $function();

        // Log after execution
        Log::info("Executed function: $functionName", ['result' => $result]);

        return $result;
    }
}
