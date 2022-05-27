<?php

namespace App\Observers;

use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class KaryawanObserver
{

    public function created(Karyawan $karyawan)
    {   
        $user = new User;
        $user->name = $karyawan->nama_lengkap;
        $user->email = $karyawan->email;
        $user->password = Hash::make('P@ssw0rd');
        $user->save();
        $user->assignRole('Karyawan');

        $karyawan->update([
            'user_id' => $user->id,
        ]);
    } 

    public function updated(Karyawan $karyawan)
    {
        if($karyawan->email){
            $user = new User;
            $user->email = $karyawan->email;
            $user->update();
        }
    }

    public function deleted(Karyawan $karyawan)
    {
        $user = User::find($karyawan->user_id);
        $user->delete();
    }

}
