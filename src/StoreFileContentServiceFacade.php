<?php

namespace ByteDigital123\StoreFileContentService;

use Illuminate\Support\Facades\Facade;

/**
 * @see \ByteDigital123\StoreFileContentService\Skeleton\SkeletonClass
 */
class StoreFileContentServiceFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'storefilecontentservice';
    }
}
