<?php

namespace Buckaroo\Handlers\Logging;

interface Loggable
{
    public function setLogger(Subject $logger);
    public function getLogger(): ?Subject;
}