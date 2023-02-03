
@foreach($inventories[0]->products as $product)
    <tr id="{{$product->id}}">
        <td >{{$product->name}}</td>
        <td>{{$product->sub_sku}}</td>
        <td id="productQuantity_{{$product->id}}">{{$quantityProductsArray[$product->id]}}</td>
        <td onchange="updateInventoryAmount(this , {{$product->id}})">
            <input value="{{$product->pivot->amount_after_inventory}}" type="text" id="productAfterInventory_{{$product->id}}" disabled="true"/>
        </td>
        @php
        $amountDifference = $quantityProductsArray[$product->id] - $product->pivot->amount_after_inventory;
        @endphp
        <td id="difference_{{$product->id}}">{{$amountDifference}}</td>
        <td>
            <button class="btn btn-primary" name="edit" id="editProdcutQuantity">edit</button>
            <button class="btn btn-danger" name="delete" >delete</button>
            <i class="fa-thin fa-badge-check"></i>
        </td>
    </tr>
    <i class="fa-thin fa-badge-check"></i>
@endforeach


