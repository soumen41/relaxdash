<?php

namespace App\Http\Controllers;

use App\Models\Excel;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $getData = Excel::all();
        return view('home', compact('getData'));
    }

    public function import(Request $request)
    {
    $file = $request->file('file');
    $fileContents = file($file->getPathname());
    foreach ($fileContents as $key=> $line) {
        if($key == 0){
            continue;
        }
        $data = str_getcsv($line);
        Excel::create([
            'order_id' => $data[0],
            //'price' => $data[1],
            // Add more fields as needed
        ]);
    }

    return redirect()->back()->with('success', 'CSV file imported successfully.');
    }
}
