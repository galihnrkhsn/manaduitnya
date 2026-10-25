<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public const COLORS = [
        '#0D9488', '#14B8A6', '#0369A1', '#B45309', '#BE185D', '#65A30D',
        '#15803D', '#475569', '#64748B', '#94A3B8', '#C2410C', '#9F1239',
    ];

    public function index(Request $request)
    {
        $all = $request->user()->categories()->orderBy('name')->get();
        $active = $all->whereNull('archived_at');

        return view('categories.index', [
            'sections' => [
                ['title' => 'Pengeluaran', 'type' => Transaction::EXPENSE,
                 'items' => $active->where('type', Transaction::EXPENSE)],
                ['title' => 'Pemasukan', 'type' => Transaction::INCOME,
                 'items' => $active->where('type', Transaction::INCOME)],
            ],
            'archived' => $all->whereNotNull('archived_at'),
        ]);
    }

    public function create(Request $request)
    {
        // ?jenis=1 atau ?jenis=2 dari tombol "Tambah" di tiap bagian
        $type = $request->integer('jenis');
        if (! in_array($type, [Transaction::INCOME, Transaction::EXPENSE])) {
            $type = Transaction::EXPENSE;
        }

        return view('categories.form', [
            'category' => new Category(['type' => $type, 'color' => self::COLORS[0]]),
            'colors'   => self::COLORS,
        ]);
    }

    public function store(Request $request)
    {
        $type = (int) $request->input('type');
        abort_unless(in_array($type, [Transaction::INCOME, Transaction::EXPENSE]), 422);

        $data = $request->validate($this->rules($request, $type), $this->messages());

        $request->user()->categories()->create($data + ['type' => $type]);

        return redirect()->route('categories.index')->with('status', 'Kategori ditambahkan.');
    }

    public function edit(Category $category)
    {
        $this->own($category);

        return view('categories.form', ['category' => $category, 'colors' => self::COLORS]);
    }

    public function update(Request $request, Category $category)
    {
        $this->own($category);

        // Jenis tidak bisa diubah, karena sudah terpakai di transaksi dan budget
        $data = $request->validate($this->rules($request, $category->type, $category->id), $this->messages());
        $category->update($data);

        return redirect()->route('categories.index')->with('status', 'Kategori diperbarui.');
    }

    // "Hapus" di sini berarti arsipkan, supaya riwayat transaksi tetap utuh
    public function destroy(Category $category)
    {
        $this->own($category);

        $category->archived_at = now();
        $category->save();

        return redirect()->route('categories.index')->with('status', 'Kategori diarsipkan.');
    }

    public function restore(Category $category)
    {
        $this->own($category);

        $category->archived_at = null;
        $category->save();

        return redirect()->route('categories.index')->with('status', 'Kategori dipulihkan.');
    }

    private function own(Category $category): void
    {
        abort_unless($category->user_id === request()->user()->id, 404);
    }

    private function rules(Request $request, int $type, ?int $ignoreId = null): array
    {
        return [
            'name' => [
                'required', 'string', 'max:50',
                Rule::unique('categories')
                    ->where('user_id', $request->user()->id)
                    ->where('type', $type)
                    ->whereNull('archived_at')
                    ->ignore($ignoreId),
            ],
            'color' => ['required', Rule::in(self::COLORS)],
        ];
    }

    private function messages(): array
    {
        return [
            'name.required'  => 'Nama kategori wajib diisi.',
            'name.max'       => 'Nama kategori maksimal 50 karakter.',
            'name.unique'    => 'Nama kategori ini sudah ada.',
            'color.required' => 'Pilih salah satu warna.',
            'color.in'       => 'Pilih salah satu warna.',
        ];
    }
}
