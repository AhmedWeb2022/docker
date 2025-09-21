<?php

namespace App\Modules\Diploma\Application\Pipelines;
use Closure;
class HandleCreateDiplomaPipeline
{
    protected array $steps;
    public function __construct(array $steps)
    {
        $this->steps = $steps;
    }
    public function handle(array $payload)
    {
        return array_reduce(
            array_reverse($this->steps),
            fn ($next, $step) => fn ($payload) => (new $step())->handle($payload, $next),
            fn ($payload) => $payload // Final step, just return the payload
        )($payload);
        
    }
}

