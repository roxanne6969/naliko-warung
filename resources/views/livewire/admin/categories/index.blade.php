<div class="space-y-8">
    <div class="flex flex-col gap-4 rounded-3xl bg-[#6B543B] px-6 py-5 text-[#F6E8D4] shadow-sm ring-1 ring-black/5 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <flux:heading size="lg" class="tracking-[0.12em]">Kategori Produk</flux:heading>
            <flux:text class="text-sm text-[#E8D3B3]">Kelola kategori menu untuk kafe, minuman, dan makanan dengan cepat.</flux:text>
        </div>

        <flux:button variant="primary" class="bg-[#E7D1AE] text-[#3F2E1D] hover:bg-[#F0DEC4]" wire:click="createCategory">
            + Tambah Kategori
        </flux:button>
    </div>

    <div class="rounded-[2rem] bg-[#F7E9D2] p-5 shadow-[0_18px_50px_rgba(0,0,0,0.08)] ring-1 ring-black/5">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <flux:heading size="md" class="text-[#2F2418]">Daftar kategori</flux:heading>
                <flux:text class="text-sm text-[#745A3F]">Klik edit untuk mengganti nama, atau hapus jika tidak ada produk yang memakai.</flux:text>
            </div>

            <div class="w-full sm:max-w-sm">
                <flux:input wire:model.live.debounce.300ms="search" placeholder="Cari kategori..." icon="magnifying-glass" />
            </div>
        </div>

        <div class="mt-6 overflow-hidden rounded-2xl border border-[#D9C2A4] bg-[#FCF4E7]">
            <table class="min-w-full divide-y divide-[#E5D4BD]">
                <thead class="bg-[#E8D4B7] text-left text-xs font-semibold uppercase tracking-[0.16em] text-[#5F4932]">
                    <tr>
                        <th class="px-5 py-4">Nama Kategori</th>
                        <th class="px-5 py-4 text-center">Jumlah Produk</th>
                        <th class="px-5 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EFE1CF]">
                    @forelse ($categories as $category)
                        <tr class="transition hover:bg-[#F7EAD8]">
                            <td class="px-5 py-4">
                                <div class="font-semibold text-[#2F2418]">{{ $category->category_name }}</div>
                                <div class="text-sm text-[#8A6A49]">Kategori menu kafe</div>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center rounded-full bg-[#6B543B] px-3 py-1 text-sm font-medium text-[#F7E9D8]">
                                    {{ $category->menu_items_count }} produk
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex gap-2">
                                    <flux:button size="sm" variant="filled" class="bg-[#E3CFB0] text-[#4B3825] hover:bg-[#DABF95]" wire:click="editCategory({{ $category->id }})">
                                        Edit
                                    </flux:button>
                                    <flux:button size="sm" variant="danger" wire:click="confirmDelete({{ $category->id }})">
                                        Hapus
                                    </flux:button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-5 py-10 text-center text-[#7A5C3D]">
                                Belum ada kategori. Tambahkan kategori pertama untuk menu kafe Anda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <flux:modal wire:model="showFormModal" class="max-w-2xl">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $editingId ? 'Edit Kategori' : 'Tambah Kategori' }}</flux:heading>
                <flux:text class="text-sm text-[#7A5C3D]">Gunakan nama yang singkat dan jelas, misalnya Minuman Espresso atau Snack Ringan.</flux:text>
            </div>

            <div>
                <flux:field>
                    <flux:label>Nama kategori</flux:label>
                    <flux:input wire:model="category_name" placeholder="Contoh: Kopi Susu" autofocus />
                    <flux:error name="category_name" />
                </flux:field>
            </div>

            <div class="flex items-center justify-end gap-3">
                <flux:modal.close>
                    <flux:button variant="filled">Batal</flux:button>
                </flux:modal.close>
                <flux:button variant="primary" wire:click="saveCategory">
                    Simpan
                </flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal wire:model="showDeleteModal" class="max-w-xl">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Hapus kategori?</flux:heading>
                <flux:text class="text-sm text-[#7A5C3D]">Kategori hanya bisa dihapus jika belum dipakai oleh produk menu.</flux:text>
            </div>

            <div class="rounded-2xl bg-[#F3E1C8] p-4 text-sm text-[#5F4932]">
                Pastikan kategori ini memang tidak diperlukan lagi sebelum dihapus.
            </div>

            <div class="flex items-center justify-end gap-3">
                <flux:modal.close>
                    <flux:button variant="filled">Batal</flux:button>
                </flux:modal.close>
                <flux:button variant="danger" wire:click="deleteCategory">
                    Hapus
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
