<?php

// Using Respect\Validation on Forms

// This is a sample on how to use the bare Respect\Validation and pure PHP to
// validate forms and return sensible feedback about the errors.

// Additional comments are available after the 80th column of text per line.
// Keep up with the $validAccount and $invalidAccount, they're the real samples.

/* Configuration, responsible for loading and preparing */

require 'vendor/autoload.php';                                            // Requiring libraries from the composer installation.

use Respect\Validation\Validator as v;                                          // Aliasing the validator with a short name for easy usage.
use Respect\Validation\Exceptions\ValidationException;                          // Aliasing the exception we use to catch validation errors.

print v::intVal()->max(15)->validate(15); // false
//print v::intVal()->max(20)->validate(20); // false

?>
