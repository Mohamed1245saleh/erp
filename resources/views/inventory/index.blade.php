@extends('layouts.app')
@section('title', __('inventory.inventory'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('inventory.stock_inventory')</h1>
        <h3>@lang('inventory.create_new_inventory')</h3>
        <!-- <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol> -->
    </section>

    <!-- Main content -->
    <section class="content">
        <form method="post" action="{{url("createNewInventory")}}">
            @csrf
        <div class="row">
            <label style="margin:17px">@lang("inventory.inventory_start_date")</label></br>
            <div class="col-md-6">
                <div class="input-group">
						<span class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</span>
                    <input style="height: 45px" class="form-control"  required="" name="inventory_start_date" type="date" >
                </div>
            </div>
        </div>
        <div class="row">
            <label style="margin:17px">@lang("inventory.inventory_end_date")</label></br>
            <div class="col-md-6">
                <div class="input-group">
						<span class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</span>
                    <input style="height: 45px" class="form-control"  required="" name="inventory_end_date" type="date" >
                </div>
            </div>
        </div>
        <div class="row">
            <label style="margin:17px">@lang("inventory.inventory_start_date")</label></br>
            <div class="col-md-6">
                <div class="input-group">
						<span class="input-group-addon">
							<i class="fa fa-code-branch"></i>
						</span>
                    <select class="form-control" name="branch">
                        <option id="1" value="1">Main Branch</option>
                        <option id="2" value="2">Secondary Branch</option>
                    </select>
                </div>
            </div>
        </div>
    </br>
    </br>
        <button type="submit" class="btn btn-primary">حفظ</button>
        </form>
    </section>
    <!-- /.content -->

@endsection

