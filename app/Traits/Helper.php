<?php

namespace App\Traits;

trait Helper
{
    public function toPagination($data): array
    {
        $transform = collect($data);
        return [
            'data'=>$transform->get('data'),
            'paginate'=>$transform->except(['data','links'])
        ];
    }

}
