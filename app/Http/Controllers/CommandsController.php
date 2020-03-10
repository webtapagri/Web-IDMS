<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Artisan;

class CommandsController extends Controller
{
	private $cmd = ['migrate','optimize','idms:clean_master_sap','idms:clean_master_idms'];
	
    public function run($cmd)
	{
		if(in_array($cmd, $this->cmd)){
			$exitCode = Artisan::call($cmd);
		}else{
			return '<p style="color:red;">command not found!</p>';
		}
		
		return $exitCode == 0 ? '<p style="color:green;">Command executed!</p>' : '<p style="color:orange;">Something wrong!</p>';
	}
}
