<?php

namespace App\Livewire\Forms;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Form;
use Livewire\WithFileUploads;

class ProductForm extends Form
{
    use WithFileUploads;

    public ?Product $productModel = null;

    public string $name = '';
    public ?int $category_id = null;
    public string $description = '';
    public string $price = ''; // string dulu di form, di-cast ke int saat simpan
    public string $stock = '';
    public bool $is_active = true;

    /** @var \Livewire\Features\SupportFileUploads\TemporaryUploadedFile[] */
    public array $newImages = [];

    public function rules(): array
    {
        return [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('products', 'name')->ignore($this->productModel?->id),
            ],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'newImages' => ['nullable', 'array', 'max:5'],
            'newImages.*' => ['image', 'max:2048'], // max 2MB per gambar
        ];
    }

    public function setProduct(Product $product): void
    {
        $this->productModel = $product;
        $this->name = $product->name;
        $this->category_id = $product->category_id;
        $this->description = $product->description ?? '';
        $this->price = (string) $product->price;
        $this->stock = (string) $product->stock;
        $this->is_active = $product->is_active;
        $this->newImages = [];
    }

    public function store(): void
    {
        $this->validate();

        $product = Product::create([
            'category_id' => $this->category_id,
            'name' => $this->name,
            'slug' => $this->generateSlug(),
            'description' => $this->description,
            'price' => (int) $this->price,
            'stock' => (int) $this->stock,
            'is_active' => $this->is_active,
        ]);

        $this->storeImages($product);
        $this->reset();
    }

    public function update(): void
    {
        $this->validate();

        $this->productModel->update([
            'category_id' => $this->category_id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => (int) $this->price,
            'stock' => (int) $this->stock,
            'is_active' => $this->is_active,
        ]);

        $this->storeImages($this->productModel);
        $this->newImages = [];
        $this->reset(['productModel', 'name', 'category_id', 'description', 'price', 'stock', 'is_active']);
    }

    private function generateSlug(): string
    {
        return Str::slug($this->name) . '-' . random_int(1000, 9999);
    }

    private function storeImages(Product $product): void
    {
        $hasExistingPrimary = $product->images()->where('is_primary', true)->exists();
        $nextSort = $product->images()->max('sort_order') + 1;

        foreach ($this->newImages as $index => $image) {
            $path = $image->store('products', 'public');

            $product->images()->create([
                'path' => $path,
                'is_primary' => ! $hasExistingPrimary && $index === 0,
                'sort_order' => $nextSort + $index,
            ]);
        }
    }
}