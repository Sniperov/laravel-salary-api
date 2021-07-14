<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SalaryResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'finalSalary' => $this['finalSalary'],
            'taxes' => [
                'cshcci' => array_key_exists('cshcci', $this['taxes']) ? $this['taxes']['cshcci'] : null,
                'cpc' => array_key_exists('cpc', $this['taxes']) ? $this['taxes']['cpc'] : null,
                'cshi' => array_key_exists('cshi', $this['taxes']) ? $this['taxes']['cshi']  : null,
                'ssc' => array_key_exists('ssc', $this['taxes']) ? $this['taxes']['ssc'] : null,
                'iit' => array_key_exists('iit', $this['taxes']) ? $this['taxes']['iit'] : null,
            ],
        ];
    }
}
