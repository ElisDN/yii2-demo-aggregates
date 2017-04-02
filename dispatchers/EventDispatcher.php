<?php

namespace app\dispatchers;

interface EventDispatcher
{
    public function dispatch(array $events): void;
}