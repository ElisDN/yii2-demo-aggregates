<?php

namespace app\repositories;

use ProxyManager\Factory\LazyLoadingValueHolderFactory;

trait LazyLoadTrait
{
    /**
     * @return object|LazyLoadingValueHolderFactory
     */
    protected static function getLazyFactory()
    {
        return \Yii::createObject(LazyLoadingValueHolderFactory::class);
    }
}