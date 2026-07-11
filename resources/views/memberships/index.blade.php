<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-gray-800">Memberships</h2></x-slot>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <form method="GET" class="mb-4 flex gap-2"><input name="search" value="{{ request('search') }}" placeholder="Search member" class="rounded-md border-gray-300 text-sm"><select name="status" class="rounded-md border-gray-300 text-sm"><option value="">All status</option>@foreach($statuses as $status)<option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->label() }}</option>@endforeach</select><button class="rounded-md border px-3 text-sm"><i class="bi bi-search"></i></button></form>
        <div class="overflow-hidden rounded-md border bg-white">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-gray-600"><tr><th class="px-4 py-3">Member</th><th>Plan</th><th>Period</th><th>Status</th><th>Payment</th><th></th></tr></thead>
                <tbody class="divide-y divide-gray-100">@forelse($memberships as $membership)<tr><td class="px-4 py-3 font-medium">{{ $membership->member->name }}</td><td>{{ $membership->plan->name }}</td><td>{{ $membership->starts_at->format('d M Y') }} - {{ $membership->expires_at->format('d M Y') }}</td><td>{{ $membership->status->label() }}</td><td>{{ $membership->payments->last()?->status->label() ?? '-' }}</td><td class="px-4 text-right"><a href="{{ route('memberships.show', $membership) }}">View</a></td></tr>@empty @include('shared.table-empty', ['cols' => 6]) @endforelse</tbody>
            </table>
        </div>
        <div class="mt-4">{{ $memberships->links() }}</div>
    </div>
</x-app-layout>
