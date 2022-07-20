<?php

namespace Buckaroo\Handlers\Reply;

interface ReplyStrategy
{
    public function validate(): bool;
}