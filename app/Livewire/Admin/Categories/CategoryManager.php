<?php
namespace App\Livewire\Admin\Categories;

use App\Livewire\Forms\CategoryForm;
use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class CategoryManager extends Component
{
    use WithPagination;

    public CategoryForm $form;
    public bool $showModal = false;

    public function mount(): void
    {
        $this->authorize('viewAny', Category::class);
    }

    public function openCreate(): void
    {
        $this->authorize('create', Category::class);
        $this->form->reset();
        $this->showModal = true;
    }

    public function openEdit(Category $category): void
    {
        $this->authorize('update', $category);
        $this->form->setCategory($category);
        $this->showModal = true;
    }

    public function delete(Category $category): void
    {
        if ($category->products()->exists()) {
            $this->addError('delete', 'Kategori masih punya produk, tidak bisa dihapus.');
            return;
        }

        $category->delete();
    }

    public function render()
    {
        return view('livewire.admin.categories.category-manager', [
            'categories' => Category::withCount('products')->latest()->paginate(10),
        ]);
    }
}