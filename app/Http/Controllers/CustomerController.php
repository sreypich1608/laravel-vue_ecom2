<?php

namespace App\Http\Controllers;

use App\Models\Cart_Item;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function customer_home(){
        $new_product = Product::orderBy('id','desc')->get();

        return response()->json([
            'new_product' => $new_product
        ],200);
    }
    public function add_to_cart(Request $request){
        $customer = Auth::guard('customer')->user();
        if(!$customer){
            // If not logged in, redirect to login with intended URL
            return response()->json([
                'status' => 401,
                'msg' => 'Please login to add items to the cart.',
                'redirect' => route('customer_login', ['redirect_to' => url()->current()]),
            ]);
        }
        // if already login procceed to add item to cart
        // Check if the product already exists in the cart
        $cartItem = Cart_Item::where('customer_id', $customer->id)
            ->where('product_id', $request->id)
            ->first();

        if ($cartItem) {
            // Product exists, update quantity and subtotal
            $cartItem->quantity += $request->quantity ?? 1; // Default increment by 1 if quantity not provided
            $cartItem->subtotal = $cartItem->quantity * $cartItem->price;
            $cartItem->save();
        } else {
            // Product doesn't exist, create a new cart item
            $product = Product::find($request->id);
            $cartItem = Cart_Item::create([
                'customer_id' => $customer->id,
                'product_id' => $request->id,
                'quantity' => $product->quantity ?? 1, // Default to 1 if quantity not provided
                'price' => $product->price,
                'subtotal' => ($product->quantity ?? 1) * $product->price,
            ]);
        }
        return response()->json([
            'status' => 200,
            'msg' => 'Item added to cart successfully!',
        ]);
    }
    public function get_cart_item(){
        $customer = Auth::guard('customer')->user();
        if (!$customer) {
            return response()->json([
                'status' => 401,
                //'msg' => 'Please login to view your cart.',
            ], 401);
        }
    
        // Use DB::select for raw SQL queries with parameter binding
        $cart_item = DB::select('
            SELECT cart_items.id,cart_items.quantity, cart_items.price, cart_items.subtotal, products.name, products.image_url 
            FROM cart_items 
            INNER JOIN products ON products.id = cart_items.product_id 
            WHERE cart_items.customer_id = ?
        ', [$customer->id]);
        // $cart_item = Cart_Item::where('customer_id',$customer->id)->get();
        return response()->json([
            'cart_item' =>$cart_item
        ],200);
    }
    public function update_cart_item(Request $request){
        $cart_item = Cart_Item::find($request->id);
        if(!$cart_item){
            return response()->json([
                'message' => 'Cart item not found' 
            ]);
        }
        if($request->action == 'decrease'){
            if($cart_item->quantity > 1){
                $cart_item->quantity -= 1;
                $cart_item->subtotal = $cart_item->quantity * $cart_item->price;
                $cart_item->save();
            }
        }else if($request->action == 'increase'){
            $cart_item->quantity += 1;
            $cart_item->subtotal = $cart_item->quantity * $cart_item->price;
            $cart_item->save();
        }
        return response()->json([
            'message' => 'Cart item updated successfully' 
        ],200);
    }
    public function remove_cart_item(Request $request){
        $cart_item = Cart_Item::find($request->id);
        if(!$cart_item){
            return response()->json([
                'message' => 'Cart item not found' 
            ]);
        }
        $cart_item->delete();
        return response()->json([
            'message' => 'Cart item removed successfully' 
        ],200);
    }
    public function customer_account(){
        $customer_acc = Auth::guard('customer')->user();
        return response()->json([
            'customer_acc' =>$customer_acc
        ],200);
    }
    public function customer_update_acc(Request $request){
        $this->validate($request,[
            'id' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => "bail|required|email|unique:customers,email,$request->id",
            'password' => 'min:6',
            'repeatpassword' => 'min:6',
            'address' => 'required',
            'phone' => 'required',
        ]);
        $data = [
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'address' => $request->address,
            'phone' => $request->phone,
        ];
        if($request->password){
            if($request->password == $request->repeatpassword){
                $password = bcrypt($request->password);
                $data['password'] = $password;
            }else{
                return response()->json([
                    'errors' => 'password and repeat password you input is not the same! please check'
                ],422);
            }
        }
        $customer_acc = Customer::where('id',$request->id)->update($data);
        return response()->json([
            'customer_acc' =>$customer_acc
        ],200);
    }
    public function place_order(Request $request){
        $validated = $request->validate([
            'payment_method' => 'required|in:cash,card',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
        ]);
    
        $customer = Auth::guard('customer')->user(); // Assuming customer is authenticated
    
        // Fetch cart items for the customer
        $cartItems = Cart_Item::where('customer_id', $customer->id)->get();
        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }
    
        // Calculate total price
        $totalPrice = $cartItems->sum('subtotal');
    
        // Create order
        $order = Order::create([
            'customer_id' => $customer->id,
            'status' => 'pending',
            'total_price' => $totalPrice,
        ]);
    
        // Create order details for each product in the cart
        foreach ($cartItems as $item) {
            OrderDetail::create([
                'customer_id' => $customer->id,
                'order_id' => $order->id,
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'subtotal' => $item->quantity * $item->price,
            ]);
    
            // Update product quantities
            $product = Product::find($item->product_id);
            if ($product) {
                $product->stock -= $item->quantity;
                $product->save();
            }
        }
    
        // Create payment
        Payment::create([
            'order_id' => $order->id,
            'payment_method' => $validated['payment_method'],
            'status' => $validated['payment_method'] === 'cash' ? 'pending' : 'initiated',
            'amount' => $totalPrice,
        ]);
    
        // Clear the cart
        Cart_Item::where('customer_id', $customer->id)->delete();
    
        return response()->json(['message' => 'Order placed successfully', 'order_id' => $order->id], 200);
    }

    public function quick_view(Request $request){
        //$product = Product::find($request->product_id);
        $product = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'products.id',
                'products.image_url',
                'products.name',
                'products.description',
                'products.price',
                'products.stock',
                'categories.name as category_name'
            )
            ->where('products.id', $request->product_id)
            ->first();
        return response()->json([
            'product' => $product,
        ],200);
    }
    public function get_order(){
        $customer = Auth::guard('customer')->user();
        $order = Order::where('customer_id', $customer->id)->orderBy('created_at', 'desc')->get();
        return response()->json([
            'order' => $order
        ],200);
    }
    public function get_order_details(Request $request){
        $order_details = OrderDetail::where('order_id', $request->order_id)
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->select(
                'order_details.*',
                'products.name as product_name',
                'products.image_url'
            )
            ->get();
        return response()->json([
            'order_details' => $order_details
        ],200);
    }
}
