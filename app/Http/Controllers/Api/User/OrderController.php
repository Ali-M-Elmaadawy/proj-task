<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\OrdersCollection;
use App\Http\Requests\Api\User\PayRequest;
use App\Http\Requests\Api\User\OrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Actions\OrderProductAction;
use App\Models\PaymentMethod;
use App\Payment\PaymentStrategyLink;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
class OrderController extends Controller
{
    private $orderProductAction;

    public function __construct(OrderProductAction $orderProductAction) {

        $this->orderProductAction = $orderProductAction;
    }

    public function list() {
        $orders = Order::where('user_id' , auth('api')->user()->id)->with('payment' , 'products');

        if(request('status')) {
            $orders->where('status' , request('status'));
        }
        $orders = $orders->paginate(5);

        return response()->json([
            'status' => true,
            'data' => OrdersCollection::collection($orders)->response()->getData(true)
        ] , 200);
    }  

    public function list_of_payments() {
        $orders = Order::where('user_id' , auth('api')->user()->id)->has('payment');

        if(request('status')) {
            $orders->where('status' , request('status'));
        }
        $orders = $orders->paginate(5);

        return response()->json([
            'status' => true,
            'data' => OrdersCollection::collection($orders)->response()->getData(true)
        ] , 200);
    } 


    public function show(Order $order) {


    }


    public function store(OrderRequest $request) {

        $user = auth('api')->user();
        $requested_order = $request->requested_order;
        $products_ids = array_keys($requested_order);
        
        $products = Product::whereIn('id' , $products_ids)->get();
        $attached_with_total = $this->orderProductAction->checkProductQty($products , $requested_order);

        if($attached_with_total['status'] == false)
                return response()->json(['status' => false , 'data' => $attached_with_total['data']]);


        DB::transaction(function () use($user , $requested_order , $products , $attached_with_total) {
            $order_info = json_encode([ 'id' => $user->id , 'name' => $user->name ]);
            $order = $user->orders()->create([
                'total_price' => $attached_with_total['total_price'],
                'order_info' => $order_info
            ]);

            $this->orderProductAction->updateProductQty($products , $requested_order);


            $order->products()->attach($attached_with_total['attached_products']);
 

        });


        return response()->json(['status' => true , 'data' => 'order Created Success Please Wait The Confirmation'] , 201);

    }


    public function update(OrderRequest $request , Order $order) {
        if($order->status == 'pending' && $order->user_id == auth('api')->user()->id) {

            $user = auth('api')->user();
            $requested_order = $request->requested_order;
            $products_ids = array_keys($requested_order);
            
            $products = Product::whereIn('id' , $products_ids)->get();

            $attached_with_total = $this->orderProductAction->checkProductQty($products , $requested_order , $order);

            if($attached_with_total['status'] == false)
                    return response()->json(['status' => false , 'data' => $attached_with_total['data']] , 400);


            DB::transaction(function () use($order , $user , $requested_order , $products , $attached_with_total) {
                $order->update([
                    'total_price' => $attached_with_total['total_price']
                ]);

                
                $this->orderProductAction->updateProductQty($products , $requested_order , $order);


                $order->products()->sync($attached_with_total['attached_products']);
     

            });


            return response()->json(['status' => true , 'data' => 'order Updated Success Please Wait The Confirmation'] , 200);            
        } else {
            return response()->json(['status' => false , 'data' => 'order Cant Be Updated At This Moment'] , 403);          
        }


    }


    public function pay(PayRequest $request , Order $order) {

        if($order->status == 'confirmed' && $order->user_id == auth('api')->user()->id && $order->payment == NULL) {
            $payment_method = PaymentMethod::find($request->payment_method_id);
            $payment_info = json_encode(['status' => 'successful' , 'payment_method' => $payment_method->name]);

             $strategyGateway = new PaymentStrategyLink($payment_method->id);

             $pay = $strategyGateway->pay($order->total_price);

            $order->payment()->create([
                'payment_method_id' => $request->payment_method_id,
                'status' => 'successful',
                'payment_info' => $payment_info
            ]);

            return response()->json(['status' => true , 'data' => 'order Paid Success'] , 200);           
        } else {
            if($order->status != 'confirmed')
                $message = 'Please Wait The Order Confirmation';
                
            elseif($order->user_id != auth('api')->user()->id)
                $message = 'Not Authorized';
            else
                $message = 'Please Wait The Order Confirmation';

            return response()->json(['status' => false , 'data' => $message] , 403); 
        }
    }


    public function destroy(Order $order) {
        if($order->user_id == auth('api')->user()->id && $order->payment == NULL) {

            if(in_array($order->status, ['pending' , 'confirmed'])) {
                foreach($order->products as $product) {
                    $product->qty += $product->pivot->qty;
                    $product->save();
                }                
            }

            
            $order->delete();
            return response()->json(['status' => true , 'data' => 'order Deleted Success'] , 204);           
        }

        return response()->json(['status' => false , 'data' => 'order Cant Be Deleted'] , 403);           
    }


}
