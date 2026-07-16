<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-gray-800">Members</h2></x-slot>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('status') }}</div>
        @endif
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <form method="GET" class="flex gap-2"><input name="search" value="{{ request('search') }}" placeholder="Search members" class="rounded-md border-gray-300 text-sm"><select name="status" class="rounded-md border-gray-300 text-sm"><option value="">All</option><option value="expired" @selected(request('status') === 'expired')>Expired</option></select><button class="rounded-md border px-3 text-sm"><i class="bi bi-search"></i></button></form>
            <div class="flex flex-wrap gap-2"><a href="{{ route('members.archived') }}" class="inline-flex items-center gap-2 rounded-md border px-4 py-2 text-sm font-semibold text-gray-700"><i class="bi bi-archive"></i> Archived</a><a href="{{ route('transactions.create', ['type' => 'registration']) }}" class="inline-flex items-center gap-2 rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white"><i class="bi bi-plus-lg"></i> Transaction</a></div>
        </div>
        <div class="overflow-hidden rounded-md border bg-white">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-gray-600"><tr><th class="px-4 py-3">Code</th><th>Name</th><th>Phone</th><th>Last Membership</th><th>Expires At</th><th></th></tr></thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($members as $member)
                        @php($membership = $member->latestMembership)
                        <tr><td class="px-4 py-3">{{ $member->member_code }}</td><td class="font-medium"><a href="{{ route('members.show', $member) }}">{{ $member->name }}</a><p class="text-xs font-normal text-gray-500">{{ $member->email ?: '-' }}</p></td><td>{{ $member->phone }}</td><td>{{ $membership?->plan?->name ?? '-' }} @if($membership)<span class="ml-2 rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-600">{{ $membership->status->label() }}</span>@endif</td><td>{{ $membership?->expires_at?->format('d M Y') ?? '-' }}</td><td class="px-4 text-right"><div class="flex justify-end gap-3"><a href="{{ route('members.edit', $member) }}" class="text-gray-900"><i class="bi bi-pencil-square"></i> Edit</a><a href="{{ route('transactions.create', ['type' => 'renewal', 'member_id' => $member]) }}" class="text-gray-700"><i class="bi bi-arrow-repeat"></i> Renew</a><form method="POST" action="{{ route('members.destroy', $member) }}" onsubmit="return confirm('Archive this member?')">@csrf @method('DELETE')<button class="text-red-700"><i class="bi bi-archive"></i> Archive</button></form></div></td></tr>
                    @empty
                        @include('shared.table-empty', ['cols' => 6])
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $members->links() }}</div>
    </div>
</x-app-layout>
