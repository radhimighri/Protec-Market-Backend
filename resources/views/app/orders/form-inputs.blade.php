@php $editing = isset($order) @endphp

<div class="flex flex-wrap">
    <x-inputs.group class="w-full">
        <x-inputs.text
            name="number"
            label="Number"
            value="{{ old('number', ($editing ? $order->number : '')) }}"
            maxlength="255"
            placeholder="Number"
            required
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.number
            name="total_price"
            label="Total Price"
            value="{{ old('total_price', ($editing ? $order->total_price : '')) }}"
            max="255"
            step="0.01"
            placeholder="Total Price"
        ></x-inputs.number>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.select name="stauts" label="Stauts">
            @php $selected = old('stauts', ($editing ? $order->stauts : '')) @endphp
            <option value="paid" {{ $selected == 'paid' ? 'selected' : '' }} >Paid</option>
            <option value="processing" {{ $selected == 'processing' ? 'selected' : '' }} >Processing</option>
            <option value="packed" {{ $selected == 'packed' ? 'selected' : '' }} >Packed</option>
            <option value="picked" {{ $selected == 'picked' ? 'selected' : '' }} >Picked</option>
            <option value="cancelled" {{ $selected == 'cancelled' ? 'selected' : '' }} >Cancelled</option>
        </x-inputs.select>
    </x-inputs.group>
</div>
