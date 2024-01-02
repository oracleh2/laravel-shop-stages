<?php

namespace Support;

use App\Events\SessionRegeneratedEvent;
use Closure;

class SessionRegenerator
{
    public static function run(Closure $callback = null): void
    {
        $old = session()->getId();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        if(!is_null($callback)) {
            $callback();
        }

        event(new SessionRegeneratedEvent(
            $old,
            session()->getId())
        );
    }
}
