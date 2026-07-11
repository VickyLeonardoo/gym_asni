<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-gray-800">Upload Payment Proof</h2></x-slot>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('payments.update', $membership) }}" enctype="multipart/form-data" class="rounded-md border bg-white p-6 space-y-4">
            @csrf @method('PUT')
            <p class="text-sm text-gray-600">{{ $membership->member->name }} - {{ $membership->plan->name }}</p>
            <div class="grid gap-4 sm:grid-cols-2"><div><label class="block text-sm font-medium">Amount</label><input type="number" step="0.01" name="amount" value="{{ old('amount', $membership->payments->last()?->amount ?? $membership->price) }}" class="mt-1 w-full rounded-md border-gray-300" required></div><div><label class="block text-sm font-medium">Paid At</label><input type="date" name="paid_at" value="{{ old('paid_at', now()->format('Y-m-d')) }}" class="mt-1 w-full rounded-md border-gray-300" required></div></div>
            <div><label class="block text-sm font-medium">Method</label><input name="method" value="{{ old('method', 'bank_transfer') }}" class="mt-1 w-full rounded-md border-gray-300" required></div>
            <div><label class="block text-sm font-medium">Proof</label><input type="file" name="proof" class="mt-1 w-full rounded-md border-gray-300 text-sm" required></div>
            <div><label class="block text-sm font-medium">Notes</label><textarea name="notes" class="mt-1 w-full rounded-md border-gray-300">{{ old('notes') }}</textarea></div>
            <button class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white">Upload</button>
        </form>
    </div>
</x-app-layout>
