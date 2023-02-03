@extends('layouts.app')
@section('title', __('inventory.inventory'))
@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.13.1/datatables.min.css"/>


@endsection
@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('inventory.stock_inventory')</h1>
        <!-- <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol> -->
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="box box-primary">
            <div class="box-header text-center" style="background-color:#484848;color:#EDAF11;font-size: 30px;">
                @lang("inventory.show_stock_inventory")
            </div>
        </div>

        <table cellspacing="5" cellpadding="5" border="0">
                  <tbody>

               </tbody></table>
           <table id="example" class="display nowrap" style="width:100%">
                  <thead>
                <tr>
                       <th  style="text-align: right">@lang("inventory.operation_number")</th>
                       <th  style="text-align: right">@lang("inventory.inventory_start_date")</th>
                       <th  style="text-align: right">@lang("inventory.inventory_end_date")</th>
                       <th  style="text-align: right">@lang("inventory.status")</th>
                       <th  style="text-align: right">@lang("inventory.branch")</th>
                       <th  style="text-align: right">@lang("inventory.options")</th>

                    </tr>
                  </thead>
                  <tbody>

                  @foreach ($inventories as $inventory)
                     <tr>
                         <td>{{$inventory->id}}</td>
                         <td>{{$inventory->created_at}}</td>
                         <td>{{$inventory->end_date}}</td>
                         @if($inventory->status == 1)
                             <td><i class="fa fa-lock-open"></i></td>
                         @else
                             <td><i class="fa fa-lock"></i></td>
                         @endif

                         <td>{{$inventory->branch->name}} ( {{$inventory->branch->location_id}} )</td>
                         <td>
                             <a href="{{url('makeInventory')."/".$inventory->id}}"><button class="btn btn-primary" >جرد</button></a>
                             <a href="{{url("showInventoryReports")."/".$inventory->id."/".$inventory->branch_id}}" >
                                  <button class="btn btn-primary">تقارير</button>
                             </a>
                             <a href="{{url("inventoryIncreaseReports")."/".$inventory->id."/".$inventory->branch_id}}" >
                             <button class="btn btn-primary">تقرير الزياده</button>
                             </a>
                             <a href="{{url("inventoryDisabilityReports")."/".$inventory->id."/".$inventory->branch_id}}" >
                             <button class="btn btn-primary">تقارير العجز</button>
                             </a>
                         @if($inventory->status == 1)
                              <button class="btn btn-danger">غلق</button>
                         @else
                                 <button class="btn btn-success">فتح</button>
                         @endif

                         </td>
                     </tr>
                  @endforeach
                  </tbody>
                  <tfoot>

                  </tfoot>
               </table>
    </section>
    <!-- /.content -->

@endsection

@section('javascript')
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>
    <script src="https://cdn.datatables.net/datetime/1.2.0/js/dataTables.dateTime.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#example').DataTable({
                columnDefs: [
                    {
                        targets: [0],
                        orderData: [0, 1],
                    },
                    {
                        targets: [1],
                        orderData: [1, 0],
                    },
                    {
                        targets: [4],
                        orderData: [4, 0],
                    },
                ],
            });
        });
    </script>

@endsection
