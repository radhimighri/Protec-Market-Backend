<?php

namespace App\Http\Livewire;

use App\Models\Order;
use Livewire\Component;
use App\Models\Customer;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CustomerOrdersDetail extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public Customer $customer;
    public Order $order;

    public $selected = [];
    public $editing = false;
    public $allSelected = false;
    public $showingModal = false;

    public $modalTitle = 'New Order';

    protected $rules = [
        'order.stauts' => [
            'required',
            'in:paid,processing,packed,picked,cancelled',
        ],
        'order.number' => [
            'required',
            'unique:orders,number',
            'max:255',
            'string',
        ],
    ];

    public function mount(Customer $customer)
    {
        $this->customer = $customer;
        $this->resetOrderData();
    }

    public function resetOrderData()
    {
        $this->order = new Order();

        $this->order->stauts = 'paid';

        $this->dispatchBrowserEvent('refresh');
    }

    public function newOrder()
    {
        $this->editing = false;
        $this->modalTitle = trans('crud.customer_orders.new_title');
        $this->resetOrderData();

        $this->showModal();
    }

    public function editOrder(Order $order)
    {
        $this->editing = true;
        $this->modalTitle = trans('crud.customer_orders.edit_title');
        $this->order = $order;

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
        if (!$this->order->customer_id) {
            $this->validate();
        } else {
            $this->validate([
                'order.stauts' => [
                    'required',
                    'in:paid,processing,packed,picked,cancelled',
                ],
                'order.number' => [
                    'required',
                    Rule::unique('orders', 'number')->ignore($this->order),
                    'max:255',
                    'string',
                ],
            ]);
        }

        if (!$this->order->customer_id) {
            $this->authorize('create', Order::class);

            $this->order->customer_id = $this->customer->id;
        } else {
            $this->authorize('update', $this->order);
        }

        $this->order->save();

        $this->hideModal();
    }

    public function destroySelected()
    {
        $this->authorize('delete-any', Order::class);

        Order::whereIn('id', $this->selected)->delete();

        $this->selected = [];
        $this->allSelected = false;

        $this->resetOrderData();
    }

    public function toggleFullSelection()
    {
        if (!$this->allSelected) {
            $this->selected = [];
            return;
        }

        foreach ($this->customer->orders as $order) {
            array_push($this->selected, $order->id);
        }
    }

    public function render()
    {
        return view('livewire.customer-orders-detail', [
            'orders' => $this->customer->orders()->paginate(20),
        ]);
    }
}
