<?php

set_error_handler(function ($code, $message, $file, $line) {
  throw new ErrorException($message, $code, 1, $file, $line);
});
