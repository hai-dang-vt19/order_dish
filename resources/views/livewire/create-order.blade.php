<div class="component__order">

    <h1 class="title">Order Dish</h1>
    <div class="group__btn">
        <button type="button" class="btn @if($step == 1) btnActive @endif" wire:click="pickStep(1)">Step 1</button>
        <button type="button" class="btn @if($step == 2) btnActive @endif" wire:click="pickStep(2)">Step 2</button>
        <button type="button" class="btn @if($step == 3) btnActive @endif" wire:click="pickStep(3)">Step 3</button>
        <button type="button" class="btn @if($step == 4) btnActive @endif" wire:click="pickStep(4)">Step Review</button>
    </div>
    
    {{-- step1 --}}
    @if ($step == 1)
        <form wire:submit="nStep('next')">
            <div class="form__order">
                <div class="order__item">
                    <label>Please Select a meal </label>
                    <select wire:model.live='meal' class="select__item @error('meal') border-red @enderror" required>
                        <option value="">---</option>
                        <option value="breakfast">Breakfast</option>
                        <option value="lunch">Lunch</option>
                        <option value="dinner">Dinner</option>
                    </select>
                    @error('meal') <span class="error"> <i class='bx bxs-bug'></i> {{ $message }}</span> @enderror
                </div>
                <div class="order__item">
                    <label>Please Enter Number of people</label>
                    <input type="number" min="1" max="10" step="1" wire:model.live='numberPeople' class="number__item @error('numberPeople') border-red @enderror" required>
                    @error('numberPeople') <span class="error"> <i class='bx bxs-bug'></i> {{ $message }}</span> @enderror
                </div>
            </div>
            <div class="pn flex-end">
                <button type="submit" class="btn__pn">Next</button>
            </div>
        </form>
    @endif

    {{-- step2 --}}
    @if ($step == 2)
        @php
            $lst_restaurant = array_unique($restaurant);
        @endphp
        <form wire:submit="nStep('next')">
            <div class="form__order">
                <div class="order__item">
                    <label>Please Select a Restaurant</label>
                    <select wire:model='pickRestaurant' class="select__item @error('pickRestaurant') border-red @enderror" required>
                        <option value="">---</option>
                        @foreach ($lst_restaurant as $item)
                            <option>{{ $item }}</option>
                        @endforeach
                    </select>
                    @error('pickRestaurant') <span class="error"> <i class='bx bxs-bug'></i> {{ $message }}</span> @enderror
                </div>
            </div>
            <div class="pn flex-between">
                <button type="button" class="btn__pn" wire:click="nStep('previous')">Previous</button>
                <button type="submit" class="btn__pn">Next</button>
            </div>
        </form>
    @endif

    {{-- step3 --}}
    @if ($step == 3)
        <form wire:submit="nStep3('next')">
            @php
                $uni_dish = array_unique($dish);
                $sum = 0;
                foreach ($lstDish as $val) {
                    $sum += $val['amount'];
                }
            @endphp
            <div class="lst__dish">
                <div class="form__orderStep3" >
                    <div class="order__itemStep3">
                        <label>Please Select a Dish</label>
                        <select class="select__itemStep3 @error('pickRestaurant') border-red @enderror" wire:model='pickDish' required>
                            <option value="">---</option>

                            @foreach ($uni_dish as $key => $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach
                        </select>
                        @error('pickRestaurant') <span class="error"> <i class='bx bxs-bug'></i> {{ $message }}</span> @enderror
                    </div>
                    <div class="order__itemStep3">
                        <label>Please Enter no of servings</label>
                        <input type="number" min="1" max="10" step="1" class="number__itemStep3 @error('amountDish') border-red @enderror" wire:model='amountDish' required>
                        @error('amountDish') <span class="error"> <i class='bx bxs-bug'></i> {{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            <div class="add__itemDish">
                <i class='bx bxs-plus-circle' wire:click='addDish'></i>
            </div>

            @if ($lstDish != [])
                <div class="lst__pickDish">
                    <div class="card__order">
                        @foreach ($lstDish as $key => $item)
                            <div class="card__item">
                                <sapn>{{ $key+1 }}. {{ $item['nameDish'] }}</sapn>
                                
                                <div class="amout_block">
                                    <span>Amout: 
                                        {{ $item['amount'] }}
                                    </span>
                                    <div class="ad">
                                        <i class='bx bxs-up-arrow amount_dish' wire:click="upAmountDish('{{$item['nameDish']}}')"></i>
                                        <i class='bx bxs-down-arrow amount_dish' wire:click="downAmountDish('{{$item['nameDish']}}')"></i>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            @endif

            
            <div class="pn flex-between">
                <button type="button" class="btn__pn" wire:click="nStep('previous')">Previous</button>
                <button type="submit" class="btn__pn" @if($lstDish == [] || $sum < $numberPeople) disabled @endif>Next</button>
            </div>
        </form>
    @endif

    {{-- step 4 --}}
    @if ($step == 4)
    <form>
        <div class="block__step4">
            <div class="item__step4">
                <span class="title_item">Meal: </span>{{ $meal }} 
            </div>
            <div class="item__step4">
                <span class="title_item">No of People: </span>{{ $numberPeople }} 
            </div>
            <div class="item__step4">
                <span class="title_item">Restaurant</span> {{ $pickRestaurant }}
            </div>
            <div class="item__step4 sDish">
                <span class="title_item">
                    Dishes
                </span>
                <div class="list__dish">
                    @foreach ($lstDish as $item)
                        <p>
                            {{ $item['nameDish'] }} - {{ $item['amount'] }} 
                        </p>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="pn flex-between">
            <button type="button" class="btn__pn" wire:click="nStep('previous')">Previous</button>
            <button type="button" class="btn__submit">Submit</button>
        </div>
    </form>
    @endif

    @session('alert')
        <div class="p-4 bg-green-100">
            {{ $value }}
        </div>
    @endsession
</div>
