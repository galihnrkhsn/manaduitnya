<x-app-layout>
    <x-slot name="header">Catat transaksi</x-slot>

    <form method="POST" action="{{ route('transactions.store') }}">
        @csrf
        @include('transactions._form')
    </form>
</x-app-layout>
