<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-gray-800">Renew Membership - {{ $member->name }}</h2></x-slot>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('memberships.store', $member) }}" enctype="multipart/form-data" class="rounded-md border bg-white p-6 space-y-4">
            @csrf
            <div><label class="block text-sm font-medium">Plan</label><select name="membership_plan_id" class="mt-1 w-full rounded-md border-gray-300" required>@foreach($plans as $plan)<option value="{{ $plan->id }}" @selected(old('membership_plan_id') == $plan->id)>{{ $plan->name }} - Rp {{ number_format($plan->price, 0, ',', '.') }} / {{ $plan->duration_days }} days</option>@endforeach</select></div>
            <div class="grid gap-4 sm:grid-cols-2"><div><label class="block text-sm font-medium">Starts At</label><input type="date" name="starts_at" value="{{ old('starts_at', $suggestedStartsAt) }}" class="mt-1 w-full rounded-md border-gray-300" required><p class="mt-1 text-xs text-gray-500">Default lanjut dari tanggal habis membership aktif.</p></div><div><label class="block text-sm font-medium">Override Price</label><input type="number" step="0.01" name="price" value="{{ old('price') }}" class="mt-1 w-full rounded-md border-gray-300"></div></div>
            <div class="grid gap-4 sm:grid-cols-2"><div><label class="block text-sm font-medium">Payment Amount</label><input type="number" step="0.01" name="amount" value="{{ old('amount') }}" class="mt-1 w-full rounded-md border-gray-300"></div><div><label class="block text-sm font-medium">Paid At</label><input type="date" name="paid_at" value="{{ old('paid_at') }}" class="mt-1 w-full rounded-md border-gray-300"></div></div>
            <div><label class="block text-sm font-medium">Payment Method</label><input name="method" value="{{ old('method', 'bank_transfer') }}" class="mt-1 w-full rounded-md border-gray-300"></div>
            <div><label class="block text-sm font-medium">Payment Proof</label><input type="file" name="proof" class="mt-1 w-full rounded-md border-gray-300 text-sm"></div>
            <div><label class="block text-sm font-medium">Notes</label><textarea name="notes" class="mt-1 w-full rounded-md border-gray-300">{{ old('notes') }}</textarea></div>
            <button class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white">Renew Membership</button>
        </form>
    </div>
</x-app-layout>
