<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Inventory as InventoryModel;
use App\Product;
use App\Variation;
use App\BusinessLocation;
use App\PurchaseLine as productQuantity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\inventoryProducts;

class Inventory extends Controller
{
    private $duplicatedBranchProducts = array();
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("inventory.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function show(Inventory $inventory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function edit(Inventory $inventory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Inventory $inventory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inventory $inventory)
    {
        //
    }

    public function createNewInventory(Request $request){

         $branchId = $request->branch;
         $inventoryStartDate = $request->inventory_start_date;
         $inventoryEndDate = $request->inventory_end_date;
         // as we create new inventory we will mark it as opened always till the user close it.
         $openCaseStatus = 1;
        InventoryModel::create([

                "branch_id" => $branchId ,
                "created_at" => $inventoryStartDate,
                "end_date" => $inventoryEndDate,
                "status" => $openCaseStatus
            ]
        );
        return redirect("showInventoryList");
    }

    public function showInventoryList(){
//        DB::connection()->enableQueryLog();
        $inventories = InventoryModel::with('branch')->get();
//        $queries = DB::getQueryLog();
//        $last_query = end($queries);
//        dd($last_query);
        return view("inventory.showInventoryList" , compact("inventories"));
    }


    public function makeInevtory($id){
        //
        $duplicatedProductQuantity = array();
        $quantityProductsArray = array();
        $inventories =  \App\Inventory::with("products")->where("id" , $id)->get();
//        dd($inventories[0]);
        foreach ($inventories[0]->products as $product){

            if(Session::has("duplicatedProductsPerBranch")){
                    $needle[$id] = Session::get('duplicatedProductsPerBranch');
                }else{
                    $needle[$id] = array();
                }
                if(! in_array($product->id , $needle[$id])){
                    array_push($needle[$id] , $product->id);
                    Session::put("duplicatedProductsPerBranch" , $needle[$id]);
                }







            $productQuantity = productQuantity::where("product_id" , $product->id)->get();
            if(! in_array($product->id , $duplicatedProductQuantity)){
                array_push($duplicatedProductQuantity , $product->id);
                $quantityProductsArray[$product->id] = $productQuantity[0]->quantity;
            }

        }
        return view("inventory.makeInventory")->with(
            compact("id" , "inventories" , "quantityProductsArray")
        );
    }

    /**
     * Retrieves products list.
     *
     * @return \Illuminate\Http\Response
     */
    public function getProducts($id)
    {
        $business_id  = InventoryModel::find($id);
        $business_id = $business_id->branch_id;
        if (request()->ajax()) {
            $term = request()->term;

            $check_enable_stock = true;
            if (isset(request()->check_enable_stock)) {
                $check_enable_stock = filter_var(request()->check_enable_stock, FILTER_VALIDATE_BOOLEAN);
            }

            $only_variations = false;
            if (isset(request()->only_variations)) {
                $only_variations = filter_var(request()->only_variations, FILTER_VALIDATE_BOOLEAN);
            }

            if (empty($term)) {
                return json_encode([]);
            }


            $q = Product::leftJoin(
                'variations',
                'products.id',
                '=',
                'variations.product_id'
            )
                ->where(function ($query) use ($term) {
                    $query->where('products.name', 'like', '%' . $term .'%');
                    $query->orWhere('sku', 'like', '%' . $term .'%');
                    $query->orWhere('sub_sku', 'like', '%' . $term .'%');
                })
                ->active()
                ->where('business_id', $business_id)
                ->whereNull('variations.deleted_at')
                ->select(
                    'products.id as product_id',
                    'products.name',
                    'products.type',
                    // 'products.sku as sku',
                    'variations.id as variation_id',
                    'variations.name as variation',
                    'variations.sub_sku as sub_sku'
                )
                ->groupBy('variation_id');

            if ($check_enable_stock) {
                $q->where('enable_stock', 1);
            }
            if (!empty(request()->location_id)) {
                $q->ForLocation(request()->location_id);
            }
            $products = $q->get();

            $products_array = [];
            foreach ($products as $product) {
                $products_array[$product->product_id]['name'] = $product->name;
                $products_array[$product->product_id]['sku'] = $product->sub_sku;
                $products_array[$product->product_id]['type'] = $product->type;
                $products_array[$product->product_id]['variations'][]
                    = [
                    'variation_id' => $product->variation_id,
                    'variation_name' => $product->variation,
                    'sub_sku' => $product->sub_sku
                ];
            }

            $result = [];
            $i = 1;
            $no_of_records = $products->count();
            if (!empty($products_array)) {
                foreach ($products_array as $key => $value) {
                    if ($no_of_records > 1 && $value['type'] != 'single' && !$only_variations) {
                        $result[] = [ 'id' => $i,
                            'text' => $value['name'] . ' - ' . $value['sku'],
                            'variation_id' => 0,
                            'product_id' => $key
                        ];
                    }
                    $name = $value['name'];
                    foreach ($value['variations'] as $variation) {
                        $text = $name;
                        if ($value['type'] == 'variable') {
                            $text = $text . ' (' . $variation['variation_name'] . ')';
                        }
                        $i++;
                        $result[] = [ 'id' => $i,
                            'text' => $text . ' - ' . $variation['sub_sku'],
                            'product_id' => $key ,
                            'variation_id' => $variation['variation_id'],
                        ];
                    }
                    $i++;
                }
            }else{
                $result  = array([
                    "NotFound" => true
                ]);

            }


            return json_encode($result);
        }
    }

    /**
     * Retrieves products list.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPurchaseEntryRow(Request $request)
    {

        if (request()->ajax()) {
            $product_id = $request->input('product_id');
            $variation_id = $request->input('variation_id');
            $business_id  = InventoryModel::find($request->input("inventory_id"));
            $location_id = $business_id->branch_id;










            if (!empty($product_id)) {
                $product = Product::where('id', $product_id)
                    ->first();

                $productQuantity = productQuantity::where("product_id" , $product_id)->get();
                if($productQuantity->isNotEmpty()){
                    $productQuantity = $productQuantity[0]->quantity;
                }else{
                    return "zero qauntity";
                }



                $query = Variation::where('product_id', $product_id)
                    ->with([
                        'product_variation',
                        'variation_location_details' => function ($q) use ($location_id) {
                            $q->where('location_id', $location_id);
                        }
                    ]);
                if ($variation_id !== '0') {
                    $query->where('id', $variation_id);
                }

                $variations =  $query->get();

                if(Session::has("duplicatedProductsPerBranch")){
                    $needle[$location_id] = Session::get('duplicatedProductsPerBranch');
                }else{
                    $needle[$location_id] = array();
                }
                if(! in_array($product_id , $needle[$location_id])){
                    array_push($needle[$location_id] , $product_id);
                    Session::put("duplicatedProductsPerBranch" , $needle[$location_id]);
                }else{
                    return "Product already exists in the branch";
                }

                return view('inventory.partials.tablerow')
                    ->with(compact(
                        'product',
                        'variations',
                        "productQuantity"
                    ));
            }
        }
    }

    public function updateProductQuantity(Request $request){
        $productId = $request->input("productId");
        $productQuantity= $request->input("productQuantity");
       return response()->json(array(
         'product_id'         => $productId,
         'productQuantity'    => $productQuantity
       ));
    }

    public function saveInventoryProducts(Request $request){


        $data = $request->input("info");
        for($x = 0 ; $x < count($data); $x++){

            try{
                inventoryProducts::create([
                    "inventory_id" => $data[$x]["inventory_id"],
                    "product_id" => $data[$x]["product_id"],
                    "Amount_difference" => $data[$x]["amountDifference"],
                    "amount_after_inventory" => $data[$x]["amountAfterInventory"],
                ]);
            }catch(\Exception $e){
                return $e->getMessage();
            }
        }
        Session::forget("duplicatedProductsPerBranch");
        return response()->json([
            "status" => 200
        ]);
    }

    public function showInventoryReports ($id , $branch_id){
        $notExistProductIds = array();
        $duplicatedProductQuantity = array();
        $inventories =  \App\Inventory::with("products")->where("id" , $id)->get();
        foreach ($inventories[0]->products as $product){
            array_push($notExistProductIds , $product->id);
            $productQuantity = productQuantity::where("product_id" , $product->id)->get();
            if(! in_array($product->id , $duplicatedProductQuantity)){
                array_push($duplicatedProductQuantity , $product->id);
                $quantityProductsArray[$product->id] = $productQuantity[0]->quantity;
            }

        }
        $notExistsProducts = Product::whereNotIn('id', $notExistProductIds)
        ->where("business_id" , $branch_id)
        ->get();


        return view("inventory.showInventoryReports" ,
        compact("inventories" , "notExistsProducts" , "quantityProductsArray"));
    }

    public function inventoryIncreaseReports($inventory_id , $branch_id){

        $increaseProductReport = array();

        $inventories =  \App\Inventory::with("products")->where("id" , $inventory_id)->get();
        foreach ($inventories[0]->products as $product){
            $productQuantity = productQuantity::where("product_id" , $product->id)->get();
            if($productQuantity[0]->quantity - $product->pivot->amount_after_inventory > 0){
                array_push($increaseProductReport , $product);
                $quantityProductsArray[$product->id] = $productQuantity[0]->quantity ;
            }
         }
        return view("inventory.increaseReports" , compact("increaseProductReport" , "quantityProductsArray"));
    }

    public function inventoryDisabilityReports($inventory_id , $branch_id){

        $disabilityProductReport = array();
        $quantityProductsArray = array();
        $inventories =  \App\Inventory::with("products")->where("id" , $inventory_id)->get();
        foreach ($inventories[0]->products as $product){
            $productQuantity = productQuantity::where("product_id" , $product->id)->get();
            if($productQuantity[0]->quantity - $product->pivot->amount_after_inventory < 0){
                array_push($disabilityProductReport , $product);
                $quantityProductsArray[$product->id] = $productQuantity[0]->quantity ;
            }
        }
        return view("inventory.disabilityReports" , compact("disabilityProductReport" , "quantityProductsArray"));
    }

}

