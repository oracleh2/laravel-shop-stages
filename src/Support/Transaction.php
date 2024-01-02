<?php

namespace Support;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HigherOrderTapProxy;
use Throwable;

class Transaction
{
    /**
     * @param Closure $callback
     * @param Closure|null $successCallback
     * @param Closure|null $failedCallback
     * @return HigherOrderTapProxy|mixed
     * @throws Throwable
     */
    public static function run(
        Closure $callback,
        Closure $successCallback = null,
        Closure $failedCallback = null
    )
    {
        try {
            DB::beginTransaction();
            return tap($callback(), function ($result) use ($successCallback) {
                if ($successCallback !== null) {
                    $successCallback($result);
                }
                DB::commit();
            });
        } catch (Throwable $e) {
            DB::rollBack();
            if ($failedCallback !== null) {
                $failedCallback($e);
            }

            throw $e;
        }
    }
}
