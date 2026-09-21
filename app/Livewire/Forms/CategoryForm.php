<?php 
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CategoryForm extends Form
{
    public ?Category $categoryModel = null;

    #[Validate('required|string|max:255')]
    public string $name = '';

    public function rules(): array
    {
        return [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('categories', 'name')->ignore($this->categoryModel?->id),
            ],
        ];
    }

    public function store(): void
    {
        $this->validate();

        Category::create([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
        ]);

        $this->reset();
    }

    public function update(): void
    {
        $this->validate();

        $this->categoryModel->update([
            'name' => $this->name,
            'slug' => Str::slug($this->name)
        ]);

        $this->reset();
        $this->categoryModel = null;
    }
}