<?php

namespace App\Livewire\Admin\Categories;

use App\Models\CategoriesItem;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app-sidebar')]
class Index extends Component
{
    public string $search = '';
    public string $category_name = '';
    public ?int $editingId = null;
    public ?int $deletingId = null;
    public bool $showFormModal = false;
    public bool $showDeleteModal = false;

    public function createCategory(): void
    {
        $this->reset(['category_name', 'editingId']);
        $this->resetValidation();
        $this->showFormModal = true;
    }

    public function editCategory(int $categoryId): void
    {
        $category = CategoriesItem::findOrFail($categoryId);

        $this->editingId = $category->id;
        $this->category_name = $category->category_name;
        $this->resetValidation();
        $this->showFormModal = true;
    }

    public function saveCategory(): void
    {
        $validated = $this->validate([
            'category_name' => ['required', 'string', 'max:100', 'unique:categories_items,category_name,' . $this->editingId],
        ]);

        CategoriesItem::updateOrCreate(
            ['id' => $this->editingId],
            [
                'category_name' => Str::title(trim($validated['category_name'])),
            ]
        );

        $this->showFormModal = false;
        $this->reset(['category_name', 'editingId']);
        $this->dispatch('notify', type: 'success', message: 'Kategori berhasil disimpan.');
    }

    public function confirmDelete(int $categoryId): void
    {
        $this->deletingId = $categoryId;
        $this->showDeleteModal = true;
    }

    public function deleteCategory(): void
    {
        $category = CategoriesItem::findOrFail($this->deletingId);

        if ($category->menuItems()->exists()) {
            $this->showDeleteModal = false;
            $this->dispatch('notify', type: 'danger', message: 'Kategori tidak bisa dihapus karena masih dipakai produk.');

            return;
        }

        $category->delete();

        $this->showDeleteModal = false;
        $this->deletingId = null;
        $this->dispatch('notify', type: 'success', message: 'Kategori berhasil dihapus.');
    }

    public function render()
    {
        $categories = CategoriesItem::query()
            ->withCount('menuItems')
            ->when($this->search !== '', function ($query) {
                $query->where('category_name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('category_name')
            ->get();

        $productCount = $categories->sum('menu_items_count');

        return view('livewire.admin.categories.index', [
            'categories' => $categories,
            'categoryCount' => CategoriesItem::count(),
            'productCount' => $productCount,
        ]);
    }
}
