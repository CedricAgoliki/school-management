<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }


    public function get(User $user)
    {
        return $user;
    }

    public function auth(Request $request)
    {
        $username = $request->username;
        $password = $request->password;

        $user = User::where('username', $username)->firstOrFail();

        if (Hash::check($password, $user->password)) {
            return $user;
        }

        return abort(500, 'Incorrect username or password');
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $user = new User();
        $user->fill($data);
        $user->save();
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        if (array_key_exists('password', $data) && strlen($data['password']) > 0) {
            $data['password'] = Hash::make($data['password']);
        } else {
            $data['password'] = $user->password;
        }
        $user->fill($data);
        $user->save();
    }

    public function delete(User $user)
    {
        if ($user->deletable) {
            $user->delete();
        }
    }

    public function newUser()
    {
        $user = new User;
        $user->nom = "admin";
        $user->username = "admin";
        $user->role = "admin";
        $user->password = Hash::make("admin");
        $user->deletable = false;
        $user->save();
    }

    public function destroy(User $user)
    {
        $user->delete();
    }
}
