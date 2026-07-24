<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Transaksi Member Baru</h2>
    </x-slot>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('members.store') }}" enctype="multipart/form-data" class="rounded-md border bg-white p-6">
            @include('members._form')
        </form>
    </div>
</x-app-layout>
