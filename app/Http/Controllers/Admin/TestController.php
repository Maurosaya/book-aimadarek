<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function test()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Admin controller is working',
            'timestamp' => now(),
        ]);
    }
    
    public function testView()
    {
        return view('admin.test', [
            'message' => 'Test view is working'
        ]);
    }
}
