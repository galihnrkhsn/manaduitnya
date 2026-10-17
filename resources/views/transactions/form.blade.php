{{-- Dipakai create & edit. Wrapper <form> (csrf, method) ada di halaman masing-masing. --}}
<div class="max-w-lg space-y-5" x-data="{ type: {{ (int) old('type', $transaction->type) }} }">

    {{-- Jenis: 2 pengeluaran, 1 pemasukan, 3 transfer --}}
    <div>
        <div class="grid grid-cols-3 gap-1 rounded-lg bg-card p-1">
            @foreach ([2 => ['Pengeluaran', 'peer-checked:text-red-600'], 1 => ['Pemasukan', 'peer-checked:text-green-700'], 3 => ['Transfer', 'peer-checked:text-ink']] as $value => [$label, $color])
                <label class="cursor-pointer">
                    <input type="radio" name="type" value="{{ $value }}" x-model.number="type" class="peer sr-only">
                    <span class="block rounded-md py-2.5 text-center text-sm font-medium text-ink/60 peer-checked:bg-surface peer-checked:shadow-sm {{ $color }} peer-focus-visible:ring-2 peer-focus-visible:ring-brand">
                        {{ $label }}
                    </span>
                </label>
            @endforeach
        </div>
        <x-input-error :messages="$errors->get('type')" />
    </div>

    {{-- Jumlah: tampil dengan titik ribuan, yang dikirim angka murni --}}
    <div x-data="{ raw: '{{ old('amount', $transaction->amount) }}' }">
        <x-input-label for="amount_display" value="Jumlah" />
        <div class="relative mt-1">
            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-lg font-semibold text-ink/50">Rp</span>
            <input id="amount_display" type="text" inputmode="numeric" autocomplete="off" placeholder="0"
                   :value="raw ? Number(raw).toLocaleString('id-ID') : ''"
                   @input="raw = $event.target.value.replace(/\D/g, '')"
                   class="block w-full rounded-md border-ink/20 bg-surface py-3 pl-11 pr-3 text-2xl font-semibold tabular-nums text-ink placeholder:text-ink/30 focus:border-brand focus:ring-1 focus:ring-brand">
            <input type="hidden" name="amount" :value="raw">
        </div>
        <x-input-error :messages="$errors->get('amount')" />
    </div>

    {{-- Dompet --}}
    @if ($wallets->isEmpty())
        <p class="rounded-md bg-card px-4 py-3 text-sm text-ink/70">Belum ada dompet. Tambahkan dompet dulu sebelum mencatat transaksi.</p>
    @else
        <div>
            <x-input-label for="wallet_id"><span x-text="type === 3 ? 'Dari dompet' : 'Dompet'">Dompet</span></x-input-label>
            <x-select-input id="wallet_id" name="wallet_id" class="mt-1" required>
                @foreach ($wallets as $wallet)
                    <option value="{{ $wallet->id }}" @selected(old('wallet_id', $transaction->wallet_id ?? $wallets->first()->id) == $wallet->id)>{{ $wallet->name }}</option>
                @endforeach
            </x-select-input>
            <x-input-error :messages="$errors->get('wallet_id')" />
        </div>

        {{-- Khusus transfer --}}
        <div x-show="type === 3" x-cloak>
            <x-input-label for="to_wallet_id" value="Ke dompet" />
            <x-select-input id="to_wallet_id" name="to_wallet_id" class="mt-1" x-bind:disabled="type !== 3">
                <option value="">Pilih dompet tujuan</option>
                @foreach ($wallets as $wallet)
                    <option value="{{ $wallet->id }}" @selected(old('to_wallet_id', $transaction->to_wallet_id) == $wallet->id)>{{ $wallet->name }}</option>
                @endforeach
            </x-select-input>
            <x-input-error :messages="$errors->get('to_wallet_id')" />
        </div>
    @endif

    {{-- Kategori: satu pilihan per jenis, yang tidak aktif tidak ikut terkirim --}}
    @foreach ([2 => 'pengeluaran', 1 => 'pemasukan'] as $catType => $catLabel)
        <div x-show="type === {{ $catType }}" x-cloak>
            <x-input-label for="category_{{ $catType }}" value="Kategori" />
            @if ($categories->get($catType, collect())->isEmpty())
                <p class="mt-1 text-sm text-ink/60">Belum ada kategori {{ $catLabel }}.</p>
            @else
                <x-select-input id="category_{{ $catType }}" name="category_id" class="mt-1" x-bind:disabled="type !== {{ $catType }}">
                    <option value="">Pilih kategori</option>
                    @foreach ($categories[$catType] as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $transaction->category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </x-select-input>
            @endif
            <x-input-error :messages="$errors->get('category_id')" />
        </div>
    @endforeach

    {{-- Tanggal --}}
    <div>
        <x-input-label for="transaction_date" value="Tanggal" />
        <x-text-input id="transaction_date" class="mt-1" type="date" name="transaction_date"
                      :value="old('transaction_date', $transaction->transaction_date->format('Y-m-d'))" required />
        <x-input-error :messages="$errors->get('transaction_date')" />
    </div>

    {{-- Catatan --}}
    <div>
        <x-input-label for="note" value="Catatan (opsional)" />
        <x-text-input id="note" class="mt-1" type="text" name="note" maxlength="255"
                      :value="old('note', $transaction->note)" placeholder="Misal: makan siang" />
        <x-input-error :messages="$errors->get('note')" />
    </div>

    <x-primary-button class="w-full" :disabled="$wallets->isEmpty()">Simpan</x-primary-button>
</div>
