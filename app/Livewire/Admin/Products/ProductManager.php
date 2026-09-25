<?php

namespace App\Livewire\Admin\Products;

use App\Livewire\Forms\ProductForm;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ProductManager extends Component
{
    use WithPagination;

    public ProductForm $form;
    public bool $showModal = false;

    public function mount(): void
    {
        $this->authorize('viewAny', Product::class);
    }

    public function openCreate(): void
    {
        $this->authorize('create', Product::class);
        $this->form->reset();
        $this->showModal = true;
    }

    public function openEdit(Product $product): void
    {
        $this->authorize('update', $product);
        $this->form->setProduct($product);
        $this->showModal = true;
    }

    public function save(): void
    {
        if ($this->form->productModel) {
            $this->authorize('update', $this->form->productModel);
            $this->form->update();
        } else {
            $this->authorize('create', Product::class);
            $this->form->store();
        }

        $this->showModal = false;
    }

    public function deleteImage(ProductImage $image): void
    {
        $this->authorize('update', $image->product);

        Storage::disk('public')->delete($image->path);
        $image->delete();
    }

    public function setPrimaryImage(ProductImage $image): void
    {
        $this->authorize('update', $image->product);

        $image->product->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);
    }

    public function delete(Product $product): void
    {
        $this->authorize('delete', $product);

        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $product->delete();
    }

    public function render()
    {
        return view('livewire.admin.products.product-manager', [
            'products' => Product::with(['category', 'images'])->latest()->paginate(10),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }
}