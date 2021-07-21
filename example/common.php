<?php

function handleResponse($response)
{
    echo 'RESULT : ' . ($response->isSuccess() ? 'success' : 'fail');
}

function handleException($e)
{
    echo 'ERROR : ' . $e->getMessage();
}