<?php

    namespace App\Http\Controllers\Admin;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use App\Models\User;
    use App\Models\Role;

    class UserController extends Controller
    {
        public function index(){
            $adminRole = Role::where('rol','admin')->first();
            $users=User::with('roles')->get();

            return view('admin.users.index',compact('users','adminRole'));
        }
        
        public function toggleAdmin(User $user){
            $adminRole = Role::where('rol','admin')->first();
            if($user->roles->contains($adminRole->id)){
                $user->roles()->detach($adminRole);
                session()->flash('success',"$user->name adlı kullanıcıdan admin yetkisi alındı.");
        }else{
            $user->roles()->attach($adminRole);
            session()->flash('success', "$user->name adlı kullanıcıya admin yetkisi verildi.");
        }
        return redirect()->route('admin.users.index');
    }
    }
