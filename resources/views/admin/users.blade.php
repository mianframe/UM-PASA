<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.2em] text-red-200">Admin</p>
                <h1 class="section-title mt-2">User List</h1>
                <p class="section-copy mt-2">Registered student and admin accounts using the university email restriction.</p>
            </div>
        </div>
    </x-slot>

    <div class="page-wrap space-y-6">
        <div class="table-shell">
            @if($users->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="table-head">
                            <tr>
                                <th class="px-4 py-4">Name</th>
	                                <th class="px-4 py-4">Email</th>
	                                <th class="px-4 py-4">Role</th>
	                                <th class="px-4 py-4">Joined</th>
	                                <th class="px-4 py-4">Record</th>
	                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $userItem)
                                <tr class="table-row">
                                    <td class="px-4 py-4">{{ $userItem->name }}</td>
                                    <td class="px-4 py-4 text-slate-300">{{ $userItem->email }}</td>
	                                    <td class="px-4 py-4">
	                                        <span class="badge-base border-white/15 bg-white/10 text-slate-200">{{ ucfirst($userItem->role) }}</span>
	                                    </td>
	                                    <td class="px-4 py-4 text-slate-300">{{ $userItem->created_at->format('M d, Y') }}</td>
	                                    <td class="px-4 py-4">
	                                        <a href="{{ route('admin.users.record', $userItem) }}" class="btn-secondary">View Record</a>
	                                    </td>
	                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4">{{ $users->links() }}</div>
            @else
                <div class="px-6 py-12 text-center text-slate-300">No users have been added yet.</div>
            @endif
        </div>
    </div>
</x-app-layout>
