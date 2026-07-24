<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Tambah Aset</h2>
    </x-slot>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('assets.store') }}" class="rounded-md border bg-white p-6">
            @include('assets._form')
        </form>
    </div>
</x-app-layout>
