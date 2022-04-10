<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class KaryawanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->get('id') ?? request()->route('id');
        if($id){
            $karyawan = Karyawan::find($id);
            $data = [
                'divisi_id' => 'required',
                'jam_kerja_id' => 'required',
                'nik' => 'max:16',
                'nip' => 'required|unique:karyawan,nip,'.$id,
                'nama_lengkap' => 'required',
                'email' => 'required|email|unique:users,email,'.$karyawan->user_id,
            ];
        }else{
            $data = [
                'divisi_id' => 'required',
                'jam_kerja_id' => 'required',
                'nik' => 'max:16',
                'nip' => 'required|unique:karyawan,nip',
                'nama_lengkap' => 'required',
                'email' => 'required|email|unique:users,email',
            ];
        }
        return $data;
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
