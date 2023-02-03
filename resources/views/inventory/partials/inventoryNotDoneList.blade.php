@foreach($notExistsProducts as $product)
<tr id="{{$product->id}}">
    <td >{{$product->name}}</td>
    <td>{{$product->sku}}</td>
    <td id="productQuantity_{{$product->id}}"></td>
    <td onchange="updateInventoryAmount(this , {{$product->id}})">
        <input value="0" type="text" id="productAfterInventory_{{$product->id}}"/>
    </td>
    <td id="difference_{{$product->id}}"></td>
    <td>
        <button class="btn btn-danger" name="delete" >delete</button>
        <i class="fa-thin fa-badge-check"></i>
    </td>
</tr>
<i class="fa-thin fa-badge-check"></i>
@endforeach

