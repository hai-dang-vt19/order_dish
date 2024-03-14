<?php

namespace App\Livewire;

use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Livewire\Attributes\Validate;
use Livewire\Component;


class CreateOrder extends Component
{
    public $step = 1;
    public $numberDish = 1;
    
    #[Validate('required')]
    public $meal = '';
    
    #[Validate('required|numeric|min:1|max:10')]
    public $numberPeople = 1;

    public $restaurant = [];
    public $pickRestaurant = '';

    public $dish = []; // danh sach mon an

    public $pickDish = '';
    public $amountDish = 1;
    public $lstDish = [];


    public function getDishes() {
        $dishes = File::json('assets/data/dishes.json');
        return $dishes;
    }

    public function pickStep($item){
        $this->validate();
        $this->step = $item;
    }

    public function nStep($item) {
        $this->validate();
        $newStep = ($item == 'next') ? $this->step+1 : $this->step-1 ;
        $this->step = $newStep;
    }


    public function nStep3($item) {
        $this->validate();
        if ($this->lstDish != []) {
            $newStep = ($item == 'next') ? $this->step+1 : $this->step-1 ;
            $this->step = $newStep;
        }
    }


    public function addDish(){
        if (!empty($this->pickDish)) {
            $item = [
                'nameDish'=>$this->pickDish,
                'amount'=>$this->amountDish
            ];
            array_push($this->lstDish, $item);

            

            $this->reset([
                'amountDish'
            ]);;
        }
    }

    public function upAmountDish($item){
        for ($i=0; $i < count($this->lstDish); $i++) { 
            if($this->lstDish[$i]['nameDish'] == $item){
                if ($this->lstDish[$i]['amount'] < 10) {
                    $this->lstDish[$i]['amount'] +=1;
                }
            }
        }
    }
    public function downAmountDish($item){
        for ($i=0; $i < count($this->lstDish); $i++) { 
            if($this->lstDish[$i]['nameDish'] == $item){
                $this->lstDish[$i]['amount'] -= 1;
                if ($this->lstDish[$i]['amount'] == 0 ) {
                    array_splice($this->lstDish, $i, 1);
                }
            }
        }
    }

    public function render()
    {
        $this->reset([
            'restaurant',
            'dish'
        ]);

        $dataDishes = $this->getDishes();
        
        // Lấy các nhà hàng theo điều kiện 1
        for ($r=0; $r < count($dataDishes['dishes']); $r++) { 

            foreach ($dataDishes['dishes'][$r]['availableMeals'] as $getMeal) {
                
                if ($getMeal == $this->meal) {
                    array_push($this->restaurant, $dataDishes['dishes'][$r]['restaurant']);
                }
            }
        }
        
        // Lấy các món ăn theo điệu kiện 1 & 2
        for ($n=0; $n < count($dataDishes['dishes']); $n++) { 

            $findRestaurant = array_search($this->pickRestaurant, $dataDishes['dishes'][$n]);
            
            if ($findRestaurant != false) {

                $findMeal = array_search($this->meal, $dataDishes['dishes'][$n]['availableMeals']);

                if ($dataDishes['dishes'][$n]['availableMeals'][$findMeal] == $this->meal) {
                    array_push($this->dish, $dataDishes['dishes'][$n]['name']);
                }
            }
        }
        return view('livewire.create-order');
    }
}
