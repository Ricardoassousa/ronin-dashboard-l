<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class TestCounter extends Component
{
    public $count = 0;
    public $searchName = 'Ricardo'; // Nome que estamos a filtrar e editar

    public function increment()
    {
        $this->count++;
    }

    public function render()
    {
        return view('livewire.counter', [
            // A lista é recalculada sempre que o componente "acorda"
            'users' => User::where('name', 'like', '%' . $this->searchName . '%')->take(5)->get()
        ]);
    }
}