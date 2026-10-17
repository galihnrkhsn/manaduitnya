<x-app-layout>
    <x-slot name="header">Ubah transaksi</x-slot>

    <form method="POST" action="{{ route('transactions.update', $transaction) }}">
        @csrf
        @method('PUT')
        @include('transactions._form')
    </form>

    <div class="mt-6 max-w-lg border-t border-ink/10 pt-4">
        <button type="button" x-data x-on:click="$dispatch('open-modal', 'delete-transaction')"
                class="rounded-md px-1 py-2 text-sm font-medium text-red-600 hover:underline">
            Hapus transaksi
        </button>
    </div>

    <x-modal name="delete-transaction" maxWidth="sm">
        <form method="POST" action="{{ route('transactions.destroy', $transaction) }}" class="p-6">
            @csrf
            @method('DELETE')

            <h2 class="text-lg font-semibold">Hapus transaksi ini?</h2>
            <p class="mt-2 text-sm text-ink/70">Transaksi yang dihapus tidak bisa dikembalikan.</p>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                <x-danger-button>Hapus</x-danger-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
