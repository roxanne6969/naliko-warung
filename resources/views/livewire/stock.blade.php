<div class="space-y-8">
    <div class="flex flex-col gap-4 rounded-[2rem] bg-[#6B543B] px-6 py-5 text-[#F6E8D4] shadow-sm ring-1 ring-black/5 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <flux:heading size="lg" class="tracking-[0.12em]">Daftar Produk</flux:heading>
            <flux:text class="text-sm text-[#E8D3B3]">Kelola foto, harga, stok, dan status ketersediaan menu kafe.</flux:text>
        </div>

        <flux:button variant="primary" class="bg-[#E7D1AE] text-[#3F2E1D] hover:bg-[#F0DEC4]" wire:click="createProduct">
            + Tambah Produk
        </flux:button>
    </div>

    <div class="rounded-[2rem] bg-[#F7E9D2] p-5 shadow-[0_18px_50px_rgba(0,0,0,0.08)] ring-1 ring-black/5">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <flux:heading size="md" class="text-[#2F2418]">Produk kafe</flux:heading>
                <flux:text class="text-sm text-[#745A3F]">Klik status untuk cepat ubah tersedia atau habis.</flux:text>
            </div>

            <div class="w-full sm:max-w-sm">
                <flux:input wire:model.live.debounce.300ms="search" placeholder="Cari produk atau kategori..." icon="magnifying-glass" />
            </div>
        </div>

        <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
            @forelse ($products as $product)
                <div class="flex h-full flex-col overflow-hidden rounded-[1.6rem] bg-[#7A6247] text-[#F8EAD5] shadow-sm ring-1 ring-black/10">
                    <div class="bg-[#E9D7BF] p-3">
                        @php($productImageUrl = $this->resolveImageUrl($product->image_url))

                        @if ($productImageUrl)
                            <div class="relative aspect-square w-full overflow-hidden rounded-2xl shadow-sm">
                                <img src="{{ $productImageUrl }}" alt="{{ $product->name }}" class="h-full w-full object-cover object-center" onerror="this.style.display='none'; this.nextElementSibling?.classList.remove('hidden');" />
                                <div class="hidden absolute inset-0 flex items-center justify-center rounded-2xl border-2 border-dashed border-[#C8AE88] bg-[#E9D7BF] text-[#8A6A49]">
                                    <div class="text-center">
                                        <div class="text-3xl">+</div>
                                        <div class="mt-1 text-xs uppercase tracking-[0.2em]">Foto produk</div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="flex aspect-square w-full items-center justify-center rounded-2xl border-2 border-dashed border-[#C8AE88] bg-[#E9D7BF] text-[#8A6A49]">
                                <div class="text-center">
                                    <div class="text-3xl">+</div>
                                    <div class="mt-1 text-xs uppercase tracking-[0.2em]">Foto produk</div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-1 flex-col gap-3 p-4">
                        <div>
                            <div class="text-xs uppercase tracking-[0.16em] text-[#D6BE9D]">{{ $product->category?->category_name ?? 'Tanpa kategori' }}</div>
                            <div class="mt-1 text-lg font-semibold text-white">{{ $product->name }}</div>
                            <div class="text-sm text-[#EBD7B9]">{{ $product->description ?: 'Deskripsi belum diisi.' }}</div>
                        </div>

                        <div class="flex items-center justify-between text-sm">
                            <div class="text-[#F0DEC4]">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            <div class="text-[#F0DEC4]">Stok {{ $product->stock }}</div>
                        </div>

                        <div class="mt-auto flex items-center justify-between gap-2 pt-2">
                            <flux:button
                                size="sm"
                                variant="filled"
                                class="bg-[#F5E4C8] text-[#4B3825] hover:bg-[#EFD8B8]"
                                wire:click="editProduct({{ $product->id }})"
                            >
                                Edit
                            </flux:button>

                            <flux:button
                                size="sm"
                                variant="filled"
                                class="{{ $product->is_available ? 'bg-[#4E8C5C] text-white hover:bg-[#3F774C]' : 'bg-[#B63A32] text-white hover:bg-[#9F2F29]' }}"
                                wire:click="toggleAvailability({{ $product->id }})"
                            >
                                {{ $product->is_available ? 'Tersedia' : 'Habis' }}
                            </flux:button>

                            <flux:button size="sm" variant="danger" wire:click="confirmDelete({{ $product->id }})">
                                Hapus
                            </flux:button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full rounded-3xl border border-dashed border-[#C8AE88] bg-[#FCF4E7] p-10 text-center text-[#7A5C3D]">
                    Belum ada produk. Tambahkan menu pertama untuk kafe Anda.
                </div>
            @endforelse
        </div>
    </div>

    @if ($showFormModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 px-4 py-6">
            <div class="w-full max-w-5xl overflow-hidden rounded-[2rem] bg-[#F5E2C7] shadow-2xl ring-1 ring-black/10">
                <div class="flex items-center justify-between bg-[#6B543B] px-6 py-4 text-[#F6E8D4]">
                    <div>
                        <div class="text-2xl font-semibold">{{ $editingId ? 'Edit Produk' : 'Produk Baru' }}</div>
                        <div class="text-sm text-[#E8D3B3]">Form produk dengan gaya kafe, ringkas dan cepat diisi.</div>
                    </div>
                    <button type="button" wire:click="$set('showFormModal', false)" class="text-3xl leading-none text-[#E8D3B3]">&times;</button>
                </div>

                <div class="grid gap-6 p-6 lg:grid-cols-[280px_minmax(0,1fr)]">
                    <div class="rounded-[1.6rem] bg-[#8A7256] p-5 text-[#F8EAD5] shadow-sm">
                        <div class="text-sm font-medium tracking-[0.12em]">Foto Produk</div>
                        <label class="mt-4 flex min-h-72 cursor-pointer flex-col items-center justify-center rounded-[1.4rem] border-2 border-dashed border-[#E6D4B7] bg-[#F4E4CA] p-4 text-center text-[#6D5338]">
                            @if ($photo)
                                <div class="mb-4 aspect-square w-full max-w-56 overflow-hidden rounded-2xl bg-[#E9D7BF] shadow-sm">
                                    <img src="{{ $photo->temporaryUrl() }}" alt="Preview" class="h-full w-full object-cover object-center" />
                                </div>
                            @elseif ($existing_image_url)
                                <div class="mb-4 aspect-square w-full max-w-56 overflow-hidden rounded-2xl bg-[#E9D7BF] shadow-sm">
                                    <img src="{{ $this->resolveImageUrl($existing_image_url) }}" alt="Preview" class="h-full w-full object-cover object-center" onerror="this.style.display='none'; this.nextElementSibling?.classList.remove('hidden');" />
                                    <div class="hidden absolute inset-0 flex items-center justify-center rounded-2xl border-2 border-dashed border-[#C8AE88] bg-[#E9D7BF] text-[#8A6A49]">
                                        <div class="text-center">
                                            <div class="text-3xl">+</div>
                                            <div class="mt-1 text-xs uppercase tracking-[0.2em]">Foto produk</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="hidden mb-4 flex aspect-square w-full max-w-56 items-center justify-center rounded-2xl border-2 border-dashed border-[#C8AE88] bg-[#E9D7BF] text-[#8A6A49]">
                                    <div class="text-center">
                                        <div class="text-3xl">+</div>
                                        <div class="mt-1 text-xs uppercase tracking-[0.2em]">Foto produk</div>
                                    </div>
                                </div>
                            @else
                                <div class="mb-4 flex h-20 w-20 items-center justify-center rounded-full border border-[#D9C2A4] text-4xl text-[#AA8B63]">+</div>
                                <div class="text-sm font-semibold">Klik untuk upload</div>
                                <div class="mt-1 text-xs uppercase tracking-[0.18em]">Upload</div>
                            @endif
                            <input type="file" wire:model="photo" accept="image/*" class="hidden" />
                        </label>
                        <flux:error name="photo" class="mt-3" />
                    </div>

                    <div class="rounded-[1.6rem] bg-[#8A7256] p-5 text-[#F8EAD5] shadow-sm">
                        <div class="mb-4 text-sm font-medium tracking-[0.12em]">Informasi Produk</div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-medium">Nama Produk</label>
                                <input wire:model="name" type="text" class="w-full rounded-xl border border-[#D9C2A4] bg-[#F8EAD5] px-4 py-3 text-[#3F2E1D] outline-none ring-0 focus:border-[#B9976B]" placeholder="Contoh: Iced Coffee Latte" />
                                <div class="mt-2 text-sm text-red-200">@error('name'){{ $message }}@enderror</div>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium">Kategori</label>
                                <select wire:model="category_id" class="w-full rounded-xl border border-[#D9C2A4] bg-[#F8EAD5] px-4 py-3 text-[#3F2E1D] outline-none focus:border-[#B9976B]">
                                    <option value="">Pilih kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                                <div class="mt-2 text-sm text-red-200">@error('category_id'){{ $message }}@enderror</div>
                            </div>

                            <div class="md:col-span-2">
                                <label class="mb-2 block text-sm font-medium">Deskripsi</label>
                                <textarea wire:model="description" rows="3" class="w-full rounded-xl border border-[#D9C2A4] bg-[#F8EAD5] px-4 py-3 text-[#3F2E1D] outline-none focus:border-[#B9976B]" placeholder="Deskripsi singkat produk"></textarea>
                                <div class="mt-2 text-sm text-red-200">@error('description'){{ $message }}@enderror</div>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium">Harga</label>
                                <input wire:model="price" type="number" min="0" class="w-full rounded-xl border border-[#D9C2A4] bg-[#F8EAD5] px-4 py-3 text-[#3F2E1D] outline-none focus:border-[#B9976B]" placeholder="15000" />
                                <div class="mt-2 text-sm text-red-200">@error('price'){{ $message }}@enderror</div>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium">Stok</label>
                                <input wire:model="stock" type="number" min="0" class="w-full rounded-xl border border-[#D9C2A4] bg-[#F8EAD5] px-4 py-3 text-[#3F2E1D] outline-none focus:border-[#B9976B]" placeholder="0" />
                                <div class="mt-2 text-sm text-red-200">@error('stock'){{ $message }}@enderror</div>
                            </div>

                            <div class="md:col-span-2 flex items-center justify-between rounded-xl bg-[#745A3F] px-4 py-3">
                                <div>
                                    <div class="font-medium">Status Ketersediaan</div>
                                    <div class="text-sm text-[#E8D3B3]">Aktifkan jika produk masih tersedia untuk dijual.</div>
                                </div>
                                <label class="inline-flex cursor-pointer items-center gap-3">
                                    <input wire:model="is_available" type="checkbox" class="h-5 w-5 rounded border-[#D9C2A4] text-[#E7D1AE] focus:ring-[#E7D1AE]" />
                                    <span class="text-sm font-semibold">{{ $is_available ? 'Tersedia' : 'Habis' }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-3">
                            <button type="button" wire:click="$set('showFormModal', false)" class="rounded-xl bg-[#4F4438] px-5 py-3 font-semibold text-[#F8EAD5]">Batal</button>
                            <button type="button" wire:click="saveProduct" class="rounded-xl bg-[#F8EAD5] px-5 py-3 font-semibold text-[#3F2E1D]">{{ $editingId ? 'Simpan' : 'Tambah Produk' }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 px-4 py-6">
            <div class="w-full max-w-xl overflow-hidden rounded-[2rem] bg-[#F5E2C7] shadow-2xl ring-1 ring-black/10">
                <div class="bg-[#6B543B] px-6 py-4 text-[#F6E8D4]">
                    <div class="text-2xl font-semibold">Hapus Produk?</div>
                    <div class="text-sm text-[#E8D3B3]">Produk akan dihapus dari daftar menu.</div>
                </div>

                <div class="space-y-4 p-6 text-[#3F2E1D]">
                    <div class="rounded-2xl bg-[#E8D4B7] p-4">Pastikan produk ini sudah tidak dipakai sebelum dihapus.</div>

                    <div class="flex justify-end gap-3">
                        <button type="button" wire:click="$set('showDeleteModal', false)" class="rounded-xl bg-[#4F4438] px-5 py-3 font-semibold text-[#F8EAD5]">Batal</button>
                        <button type="button" wire:click="deleteProduct" class="rounded-xl bg-[#B63A32] px-5 py-3 font-semibold text-white">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
