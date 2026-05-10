<?php

namespace App\Livewire;

use App\Models\CategoriesItem;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app-sidebar')]
class Stock extends Component
{
    use WithFileUploads;

    public string $search = '';
    public ?int $editingId = null;
    public ?int $deletingId = null;
    public bool $showFormModal = false;
    public bool $showDeleteModal = false;

    public $photo = null;

    public ?int $category_id = null;
    public string $name = '';
    public string $description = '';
    public string $price = '';
    public string $stock = '0';
    public bool $is_available = true;
    public string $existing_image_url = '';

    public function createProduct(): void
    {
        $this->resetForm();
        $this->showFormModal = true;
    }

    public function editProduct(int $productId): void
    {
        $product = MenuItem::findOrFail($productId);

        $this->editingId = $product->id;
        $this->category_id = $product->category_id;
        $this->name = $product->name;
        $this->description = (string) $product->description;
        $this->price = (string) $product->price;
        $this->stock = (string) $product->stock;
        $this->is_available = (bool) $product->is_available;
        $this->existing_image_url = (string) $product->image_url;
        $this->photo = null;
        $this->resetValidation();
        $this->showFormModal = true;
    }

    public function saveProduct(): void
    {
        $validated = $this->validate([
            'category_id' => ['required', 'exists:categories_items,id'],
            'name' => ['required', 'string', 'max:100', Rule::unique('menu_items', 'name')->ignore($this->editingId)],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_available' => ['boolean'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $imageUrl = $this->existing_image_url;

        if ($this->photo) {
            if ($imageUrl && str_starts_with($imageUrl, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $imageUrl));
            }

            $imageUrl = Storage::url($this->photo->store('menu-items', 'public'));
        }

        MenuItem::updateOrCreate(
            ['id' => $this->editingId],
            [
                'category_id' => $validated['category_id'],
                'name' => trim($validated['name']),
                'description' => $validated['description'] ?: null,
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'is_available' => (bool) $validated['is_available'] && (int) $validated['stock'] > 0,
                'image_url' => $imageUrl,
            ]
        );

        $this->showFormModal = false;
        $this->resetForm();
        $this->dispatch('notify', type: 'success', message: 'Produk berhasil disimpan.');
    }

    public function confirmDelete(int $productId): void
    {
        $this->deletingId = $productId;
        $this->showDeleteModal = true;
    }

    public function deleteProduct(): void
    {
        $product = MenuItem::findOrFail($this->deletingId);

        if ($product->image_url && str_starts_with($product->image_url, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $product->image_url));
        }

        $product->delete();

        $this->showDeleteModal = false;
        $this->deletingId = null;
        $this->dispatch('notify', type: 'success', message: 'Produk berhasil dihapus.');
    }

    public function toggleAvailability(int $productId): void
    {
        $product = MenuItem::findOrFail($productId);

        $product->update([
            'is_available' => ! $product->is_available,
        ]);

        $this->dispatch(
            'notify',
            type: 'success',
            message: $product->is_available ? 'Produk ditandai tersedia.' : 'Produk ditandai habis.'
        );
    }

    public function resolveImageUrl(?string $imageUrl): ?string
    {
        if (! $imageUrl) {
            return null;
        }

        if (str_starts_with($imageUrl, 'http://') || str_starts_with($imageUrl, 'https://')) {
            return $imageUrl;
        }

        if (str_starts_with($imageUrl, '/storage/')) {
            return asset(ltrim($imageUrl, '/'));
        }

        if (str_starts_with($imageUrl, 'storage/')) {
            return asset($imageUrl);
        }

        if (Storage::disk('public')->exists($imageUrl)) {
            return Storage::disk('public')->url($imageUrl);
        }

        return asset($imageUrl);
    }

    public function resetForm(): void
    {
        $this->reset([
            'editingId',
            'category_id',
            'name',
            'description',
            'price',
            'stock',
            'is_available',
            'photo',
            'existing_image_url',
        ]);

        $this->stock = '0';
        $this->is_available = true;
        $this->resetValidation();
    }

    public function render()
    {
        $products = MenuItem::query()
            ->with('category')
            ->when($this->search !== '', function ($query) {
                $query->where(function ($builder) {
                    $builder->where('name', 'like', '%' . $this->search . '%')
                        ->orWhereHas('category', function ($categoryQuery) {
                            $categoryQuery->where('category_name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->latest()
            ->get();

        return view('livewire.stock', [
            'products' => $products,
            'categories' => CategoriesItem::orderBy('category_name')->get(),
            'productCount' => $products->count(),
            'availableCount' => $products->where('is_available', true)->count(),
            'soldOutCount' => $products->where('is_available', false)->count(),
        ]);
    }
}
