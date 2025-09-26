<?php

namespace Abather\TawakkalnaMessage\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Abather\TawakkalnaMessage\TawakkalnaMessage
 */
class TawakkalnaMessage extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Abather\TawakkalnaMessage\TawakkalnaMessage::class;
    }
}
