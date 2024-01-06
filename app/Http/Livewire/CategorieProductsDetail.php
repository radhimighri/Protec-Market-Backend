<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Categorie;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CategorieProductsDetail extends Component
{
    use AuthorizesRequests;

    public Categorie $categorie;
    public Product $product;
    public $productsForSelect = [];
    public $product_id = null;

    public $showingModal = false;
    public $modalTitle = 'New Product';

    protected $rules = [
        'product_id' => ['required', 'exists:products,id'],
    ];

    public function mount(Categorie $categorie)
    {
        $this->categorie = $categorie;
        $this->productsForSelect = Product::pluck('name', 'id');
        $this->resetProductData();
    }

    public function resetProductData()
    {
        $this->product = new Product();

        $this->product_id = null;

        $this->dispatchBrowserEvent('refresh');
    }

    public function newProduct()
    {
        $this->modalTitle = trans('crud.categorie_products.new_title');
        $this->resetProductData();

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

        $this->authorize('create', Product::class);

        $this->categorie->products()->attach($this->product_id, []);

        $this->hideModal();
    }

    public function detach($product)
    {
        $this->authorize('delete-any', Product::class);

        $this->categorie->products()->detach($product);

        $this->resetProductData();
    }

    public function render()
    {
        return view('livewire.categorie-products-detail', [
            'categorieProducts' => $this->categorie
                ->products()
                ->withPivot([])
                ->paginate(20),
        ]);
    }
}
