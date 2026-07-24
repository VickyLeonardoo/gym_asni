<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceContact\StoreServiceContactRequest;
use App\Http\Requests\ServiceContact\UpdateServiceContactRequest;
use App\Models\ServiceContact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceContactController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', ServiceContact::class);

        return view('service-contacts.index', [
            'contacts' => ServiceContact::query()
                ->when($request->string('search')->toString(), fn ($query, string $search) => $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%"))
                ->withCount(['assets', 'maintenances'])
                ->orderBy('name')
                ->paginate(15)
                ->withQueryString(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', ServiceContact::class);

        return view('service-contacts.create');
    }

    public function store(StoreServiceContactRequest $request): RedirectResponse
    {
        ServiceContact::query()->create($request->validated());

        return redirect()->route('service-contacts.index')->with('status', 'Kontak servis berhasil dibuat.');
    }

    public function edit(ServiceContact $serviceContact): View
    {
        $this->authorize('update', $serviceContact);

        return view('service-contacts.edit', ['contact' => $serviceContact]);
    }

    public function update(UpdateServiceContactRequest $request, ServiceContact $serviceContact): RedirectResponse
    {
        $serviceContact->update($request->validated());

        return redirect()->route('service-contacts.index')->with('status', 'Kontak servis berhasil diperbarui.');
    }

    public function destroy(ServiceContact $serviceContact): RedirectResponse
    {
        $this->authorize('delete', $serviceContact);
        $serviceContact->delete();

        return redirect()->route('service-contacts.index')->with('status', 'Kontak servis berhasil dihapus.');
    }
}
