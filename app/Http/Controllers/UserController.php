<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {

        $users = User::query();

        $users->when($request->keyword, function ($query, $keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('email', 'like', '%' . $keyword . '%');
            });
        });

        $users = $users->paginate();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $input = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        User::create($input);
        return redirect()->route('users.index')->with('success', 'Usuário adicionado com sucesso.');
    }

    public function edit(User $user)
    {
        $user->load(['profile', 'interests']);
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $input = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'exclude_if:password,null|min:6',
        ]);

        $user->fill($input);
        $user->save();
        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso.');
    }

    public function updateProfile(Request $request, User $user)
    {
        $input = $request->validate([
            'type' => 'required',
            'address' => 'nullable',
        ]);

        UserProfile::updateOrCreate(
            ['user_id' => $user->id],
            $input
        );

        return back()->with('success', 'Usuário editado com sucesso.');
    }

    public function updateInterests(Request $request, User $user)
    {

        $input = $request->validate([
            'interests' => 'nullable|array',
        ]);
        $user->interests()->delete();

        if (!empty($input['interests'])) {
            $user->interests()->createMany($input['interests']);
        }


        return back()->with('success', 'Usuário atualizado com sucesso.');
    }

    public function updateRoles(Request $request, User $user)
    {
        // dd($request->all());

        $input = $request->validate([
            'roles' => 'required|array',
        ]);

        $user->roles()->sync($input['roles']);
        return back()->with('success', 'Usuário atualizado com sucesso.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'Usuário deletado com sucesso.');
    }
}
