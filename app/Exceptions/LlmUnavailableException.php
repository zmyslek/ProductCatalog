<?php

namespace App\Exceptions;

// Thrown when the LLM provider cannot be reached or returns an unusable response, so callers can fall back gracefully.

use RuntimeException;

class LlmUnavailableException extends RuntimeException
{
    //
}
?>