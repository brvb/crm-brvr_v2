<?php

namespace App\Http\Traits;

use App\Models\Tenant\Tasks;

trait GenerateTaskReference
{
    /**
     * Generete identification number for task base on config
     *
     * @param Tasks $task
     * @return string
     */
    private function taskReference(string $number): string
    {
        $number = substr(10000 + $number, 1);
        $reference = date('Ym') . $number;
        return $reference;
    }
}
