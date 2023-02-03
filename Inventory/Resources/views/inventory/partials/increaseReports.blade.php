@foreach($increaseProductReport as $product)
    <tr id="{{$product->id}}">
        <td >{{$product->name}}</td>
        <td>{{$product->sku}}</td>
        <td id="productQuantity_{{$product->id}}">{{$quantityProductsArray[$product->id]}}</td>
        <td onchange="updateInventoryAmount(this , {{$product->id}})">
      {{$product->pivot->amount_after_inventory}}
        </td>
        @php
            $amountDifference = $quantityProductsArray[$product->id] - $product->pivot->amount_after_inventory;
        @endphp
        <td id="difference_{{$product->id}}">{{$amountDifference}}</td>
        <td>

        </td>
    </tr>
    <i class="fa-thin fa-badge-check"></i>
@endforeach
