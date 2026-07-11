<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request, UserService $service): View
    {
        $this->authorize('viewAny', User::class);

        return view('users.index', [
            'users' => $service->paginate($request->only(['search', 'role'])),
            'roles' => UserRole::cases(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', User::class);

        return view('users.create', ['roles' => UserRole::cases()]);
    }

    public function store(StoreUserRequest $request, UserService $service): RedirectResponse
    {
        $service->create($request->validated());

        return redirect()->route('users.index')->with('status', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        $this->authorize('update', $user);

        return view('users.edit', ['managedUser' => $user, 'roles' => UserRole::cases()]);
    }

    public function update(UpdateUserRequest $request, User $user, UserService $service): RedirectResponse
    {
        $service->update($user, $request->validated());

        return redirect()->route('users.index')->with('status', 'User updated successfully.');
    }

    public function destroy(User $user, UserService $service): RedirectResponse
    {
        $this->authorize('delete', $user);
        $service->delete($user);

        return redirect()->route('users.index')->with('status', 'User deleted successfully.');
    }
}
