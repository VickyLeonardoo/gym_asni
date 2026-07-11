<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function paginate(array $filters): LengthAwarePaginator
    {
        return User::query()
            ->when($filters['search'] ?? null, function ($query, string $search): void {
                $query->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
            })
            ->when($filters['role'] ?? null, fn ($query, string $role) => $query->where('role', $role))
            ->latest()
            ->paginate(15)
            ->withQueryString();
    }

    public function create(array $data): User
    {
        return DB::transaction(function () use ($data): User {
            $data['password'] = Hash::make($data['password']);

            return User::query()->create($data);
        });
    }

    public function update(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data): User {
            if (! empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $user->update($data);

            return $user->refresh();
        });
    }

    public function delete(User $user): void
    {
        DB::transaction(fn () => $user->delete());
    }
}
