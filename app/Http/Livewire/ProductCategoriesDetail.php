<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Categorie;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProductCategoriesDetail extends Component
{
    use AuthorizesRequests;

    public Product $product;
    public Categorie $categorie;
    public $categoriesForSelect = [];
    public $categorie_id = null;

    public $showingModal = false;
    public $modalTitle = 'New Categorie';

    protected $rules = [
        'categorie_id' => ['required', 'exists:categories,id'],
    ];

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->categoriesForSelect = Categorie::pluck('name', 'id');
        $this->resetCategorieData();
    }

    public function resetCategorieData()
    {
        $this->categorie = new Categorie();

        $this->categorie_id = null;

        $this->dispatchBrowserEvent('refresh');
    }

    public function newCategorie()
    {
        $this->modalTitle = trans('crud.product_categories.new_title');
        $this->resetCategorieData();

        $this->showModal();
    }

    public function showModal()
    {
        $this->resetErrorBag();
        $this->showingModal = true;
    }

    public function hideModal()
    {
        $this->showingModal = false;
    }

    public function save()
    {
        $this->validate();

        $this->authorize('create', Categorie::class);

        $this->product->categories()->attach($this->categorie_id, []);

        $this->hideModal();
    }

    public function detach($categorie)
    {
        $this->authorize('delete-any', Categorie::class);

        $this->product->categories()->detach($categorie);

        $this->resetCategorieData();
    }

    public function render()
    {
        return view('livewire.product-categories-detail', [
            'productCategories' => $this->product
                ->categories()
                ->withPivot([])
                ->paginate(20),
        ]);
    }
}
