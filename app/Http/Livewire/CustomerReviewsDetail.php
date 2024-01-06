<?php

namespace App\Http\Livewire;

use App\Models\Review;
use Livewire\Component;
use App\Models\Product;
use App\Models\Customer;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CustomerReviewsDetail extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public Customer $customer;
    public Review $review;
    public $productsForSelect = [];

    public $selected = [];
    public $editing = false;
    public $allSelected = false;
    public $showingModal = false;

    public $modalTitle = 'New Review';

    protected $rules = [
        'review.review' => ['required', 'max:255', 'string'],
        'review.product_id' => ['required', 'exists:products,id'],
    ];

    public function mount(Customer $customer)
    {
        $this->customer = $customer;
        $this->productsForSelect = Product::pluck('name', 'id');
        $this->resetReviewData();
    }

    public function resetReviewData()
    {
        $this->review = new Review();

        $this->review->product_id = null;

        $this->dispatchBrowserEvent('refresh');
    }

    public function newReview()
    {
        $this->editing = false;
        $this->modalTitle = trans('crud.customer_reviews.new_title');
        $this->resetReviewData();

        $this->showModal();
    }

    public function editReview(Review $review)
    {
        $this->editing = true;
        $this->modalTitle = trans('crud.customer_reviews.edit_title');
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
        $this->validate();

        if (!$this->review->customer_id) {
            $this->authorize('create', Review::class);

            $this->review->customer_id = $this->customer->id;
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

        foreach ($this->customer->reviews as $review) {
            array_push($this->selected, $review->id);
        }
    }

    public function render()
    {
        return view('livewire.customer-reviews-detail', [
            'reviews' => $this->customer->reviews()->paginate(20),
        ]);
    }
}
