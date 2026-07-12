<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-gray-800">Edit Service Contact</h2></x-slot>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8"><form method="POST" action="{{ route('service-contacts.update', $contact) }}" class="rounded-md border bg-white p-6">@method('PUT') @include('service-contacts._form')</form></div>
</x-app-layout>
