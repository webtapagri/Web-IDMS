<?php

namespace App\Exports;

use App\Models\VListProgressPerkerasan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class ProgressPerkerasan implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
	
	use Exportable;
	
	public function __construct(string $request)
	{
		$this->request = $request;
	}
	
    public function view():View
    {
		return view('excel.progress_perkerasan', [
            'data' => VListProgressPerkerasan::all()
        ]);
    }
}
