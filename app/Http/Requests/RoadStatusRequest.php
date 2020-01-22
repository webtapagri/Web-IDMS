<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoadStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status_name' => [
				'required', 
				Rule::unique('TM_ROAD_STATUS')->where(function ($query) {
					if(\Route::current()->getName()!= 'master.road_status_update'){
						return $query->whereRaw('deleted_at is null');
					}else{
						return $query->whereRaw('deleted_at is null')->where('id','!=',$this->get('id'));
					}
					
				})
			],
            'status_code' => [
				'required', 
				Rule::unique('TM_ROAD_STATUS')->where(function ($query) {
					if(\Route::current()->getName()!= 'master.road_status_update'){
						return $query->whereRaw('deleted_at is null');
					}else{
						return $query->whereRaw('deleted_at is null')->where('id','!=',$this->get('id'));
					}
				}),
				'numeric'
			],
        ];
    }
	
	public function messages()
	{
		
		return [
			'required'  => 'Harap bagian :attribute di isi.',
			'unique'    => ':attribute sudah digunakan',
		];
	}
}
