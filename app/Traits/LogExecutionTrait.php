<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait LogExecutionTrait
{
    public function logExecution(callable $function, string $functionName, array $params = [])
    {

        // Log before execution
        Log::info("Executing function: $functionName", ['params' => $params]);
        try {
            DB::beginTransaction();
            $result = $function();
            DB::commit();

            Log::info("Executed function: $functionName", ['result' => $result]);

            return $result ;
        }catch (\Throwable $throwable)
        {
            DB::rollBack();
            Log::warning('Exception Happen ' . $throwable->getMessage());
            return  response()->json([
                'status' =>false ,
                'message '=> $throwable->getMessage()
            ]) ;
        }
    }
}
