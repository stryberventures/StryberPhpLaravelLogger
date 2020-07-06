<?php

declare(strict_types=1);

namespace Stryber\Logger\Collectors;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class Passable
{
    /** @var Request|Response */
    public $input;
    public array $output;

    public function __construct($input, array $output)
    {
        $this->input = $input;
        $this->output = $output;
    }
}
