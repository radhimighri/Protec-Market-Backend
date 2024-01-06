<?php

namespace App\Http\Livewire;

use App\Models\Review;
use Livewire\Component;
use App\Models\Product;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProductReviewsDetail extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public Product $product;
    public Review $review;

    public $selected = [];
    public $editing = false;
    public $allSelected = false;
    public $showingModal = false;

    public $modalTitle = 'New Review';

    protected $rules = [
        'review.review' => ['required', 'string'],
    ];

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->resetReviewData();
    }

    public function resetReviewData()
    {
        $this->review = new Review();

        $this->review->review = null;

        $this->dispatchBrowserEvent('refresh');
    }

    public function newReview()
    {
        $this->editing = false;
        $this->modalTitle = trans('crud.product_reviews.new_title');
        $this->resetReviewData();

        $this->showModal();
    }

    public function editReview(Review $review)
    {
        $this->editing = true;
        $this->modalTitle = trans('crud.product_reviews.edit_title');
        $this->review = $review;

        $this->dispatchBrowserEvent('refresh');

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
        if (!$this->review->product_id) {
            $this->validate();
        } else {
            $this->validate([
                'review.review' => ['required', 'max:255', 'string'],
            ]);
        }

        if (!$this->review->product_id) {
            $this->authorize('create', Review::class);

            $this->review->product_id = $this->product->id;
        } else {
            $this->authorize('update', $this->review);
        }

        $this->review->save();

        $this->hideModal();
    }

    public function destroySelected()
    {
        $this->authorize('delete-any', Review::class);

        Review::whereIn('id', $this->selected)->delete();

        $this->selected = [];
        $this->allSelected = false;

        $this->resetReviewData();
    }

    public function toggleFullSelection()
    {
        if (!$this->allSelected) {
            $this->selected = [];
            return;
        }

        foreach ($this->product->reviews as $review) {
            array_push($this->selected, $review->id);
        }
    }

    public function render()
    {
        return view('livewire.product-reviews-detail', [
            'reviews' => $this->product->reviews()->paginate(20),
        ]);
    }
}
