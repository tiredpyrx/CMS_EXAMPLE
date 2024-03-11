<?php

namespace App\Actions;

use Illuminate\Http\Request;

class GetInputs
{
    public function execute(Request $request, ...$keys)
    {
        $data = [];
        foreach ($keys as $key) {
            $data[$key] = $request->input($key);
        }
        return $data;
    }
}
