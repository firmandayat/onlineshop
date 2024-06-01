<?php

namespace App\Http\Livewire\Product;

use App\Product;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    public $paginate = 10;
    public $search;
    public $formVisible;

    use WithPagination;

    public function mount()
    {
        $this->search = request()->query('search', $this->search);
    }

    protected $listeners = [
        'formClose' => 'formCloseHandler',
        'productStored' => 'productStoredHandler',
        // 'productUpdated' => 'productUpdatedHandler'
    ];

    protected $updatesQueryString = [
        ['search' => ['except' => '']],
    ];

    public function render()
    {

        return view('livewire.product.index', [
            'products' => $this->search === null ? 
                Product::latest()->paginate($this->paginate) :
                Product::latest()->where('title', 'like', '%' . $this->search . '%')
                ->paginate($this->paginate)
        ]);
    }

    public function formCloseHandler()
    {
        $this->formVisible = false;
    }

    public function productStoredHandler(){
        $this->formVisible = false;
        session()->flash('message', 'Your product was stored');
    }

}