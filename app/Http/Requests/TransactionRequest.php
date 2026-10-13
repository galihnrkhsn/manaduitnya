<?php

namespace App\Http\Requests;

use App\Models\Transaction;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()->id;
        $type = (int) $this->input('type');
        $isTransfer = $type === Transaction::TRANSFER;

        // Dompet/kategori harus milik user yang sedang login
        $own = fn (string $table) => Rule::exists($table, 'id')->where('user_id', $userId);

        return [
            'type' => ['required', Rule::in([Transaction::INCOME, Transaction::EXPENSE, Transaction::TRANSFER])],
            'wallet_id' => ['required', $own('wallets')],
            'to_wallet_id' => $isTransfer ? ['required', 'different:wallet_id', $own('wallets')] : ['nullable'],
            'category_id' => $isTransfer ? ['nullable'] : ['required', $own('categories')->where('type', $type)],
            'amount' => ['required', 'integer', 'min:1', 'max:999999999999'],
            'transaction_date' => ['required', 'date'],
            'note' => ['nullable', 'string', 'max:255'],
        ];
    }

    /** Data siap simpan: transfer tidak punya kategori, selain transfer tidak punya dompet tujuan. */
    public function payload(): array
    {
        $data = $this->validated();

        if ((int) $data['type'] === Transaction::TRANSFER) {
            $data['category_id'] = null;
        } else {
            $data['to_wallet_id'] = null;
        }

        return $data;
    }

    public function attributes(): array
    {
        return [
            'wallet_id' => 'Dompet',
            'to_wallet_id' => 'Dompet tujuan',
            'category_id' => 'Kategori',
            'amount' => 'Jumlah',
            'transaction_date' => 'Tanggal',
            'note' => 'Catatan',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'integer' => ':attribute harus berupa angka.',
            'min' => ':attribute minimal :min.',
            'max' => ':attribute melebihi batas.',
            'exists' => ':attribute tidak valid.',
            'date' => ':attribute tidak valid.',
            'different' => 'Dompet asal dan tujuan tidak boleh sama.',
        ];
    }
}
