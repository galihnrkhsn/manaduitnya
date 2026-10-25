@props(['name', 'value' => 0])

<div
    x-data="{
        raw: String(@js(old($name, $value))).replace(/\D/g, ''),
        get display() {
            return this.raw === '' ? '' : new Intl.NumberFormat('id-ID').format(this.raw);
        },
        set display(v) {
            this.raw = v.replace(/\D/g, '');
        },
    }"
    class="flex items-center rounded-md border border-slate-300 bg-white focus-within:border-teal-600 focus-within:ring-1 focus-within:ring-teal-600"
>
    <span class="pl-3 text-sm text-slate-500">Rp</span>
    <input id="{{ $name }}" type="text" inputmode="numeric" autocomplete="off" x-model="display"
           class="w-full border-0 bg-transparent px-2 py-2 text-sm tabular-nums focus:ring-0">
    <input type="hidden" name="{{ $name }}" :value="raw">
</div>
