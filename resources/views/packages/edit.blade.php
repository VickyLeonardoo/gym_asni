<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-gray-800">Edit Package</h2></x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('packages.update', $package) }}" class="rounded-md border bg-white p-6 space-y-4">
            @method('PUT')
            @include('packages._form', ['submit' => 'Update Package'])
        </form>
    </div>
</x-app-layout>
