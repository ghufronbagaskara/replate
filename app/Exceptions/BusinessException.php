<?php

namespace App\Exceptions;

use Exception;

class BusinessException extends Exception
{
  protected $statusCode;

  public function __construct(string $message = "", int $statusCode = 400, \Throwable $previous = null)
  {
    parent::__construct($message, 0, $previous);
    $this->statusCode = $statusCode;
  }

  public function getStatusCode(): int
  {
    return $this->statusCode;
  }

  public function render($request)
  {
    if ($request->expectsJson()) {
      return response()->json([
        'success' => false,
        'message' => $this->getMessage(),
      ], $this->statusCode);
    }

    return back()->with('error', $this->getMessage());
  }
}
