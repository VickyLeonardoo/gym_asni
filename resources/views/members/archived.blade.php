<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-gray-800">Archived Members</h2></x-slot>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('status') }}</div>
        @endif
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"><form method="GET" class="flex gap-2"><input name="search" value="{{ request('search') }}" placeholder="Search archived members" class="rounded-md border-gray-300 text-sm"><button class="rounded-md border px-3 text-sm"><i class="bi bi-search"></i></button></form><a href="{{ route('members.index') }}" class="inline-flex items-center gap-2 rounded-md border px-4 py-2 text-sm font-semibold text-gray-700"><i class="bi bi-arrow-left"></i> Active Members</a></div>
        <div class="overflow-hidden rounded-md border bg-white"><table class="min-w-full divide-y divide-gray-200 text-sm"><thead class="bg-gray-50 text-left text-gray-600"><tr><th class="px-4 py-3">Code</th><th>Name</th><th>Phone</th><th>Last Membership</th><th>Archived At</th><th></th></tr></thead><tbody class="divide-y divide-gray-100">@forelse($members as $member)@php($membership = $member->latestMembership)<tr><td class="px-4 py-3">{{ $member->member_code }}</td><td class="font-medium">{{ $member->name }}<p class="text-xs font-normal text-gray-500">{{ $member->email ?: '-' }}</p></td><td>{{ $member->phone }}</td><td>{{ $membership?->plan?->name ?? '-' }}</td><td>{{ $member->deleted_at?->format('d M Y') ?? '-' }}</td><td class="px-4 text-right"><form method="POST" action="{{ route('members.restore', $member->id) }}" class="inline">@csrf @method('PATCH')<button class="text-emerald-700"><i class="bi bi-arrow-counterclockwise"></i> Restore</button></form></td></tr>@empty @include('shared.table-empty', ['cols' => 6]) @endforelse</tbody></table></div>
        <div class="mt-4">{{ $members->links() }}</div>
    </div>
</x-app-layout>
