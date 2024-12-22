<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // work admin category
    public function add_category(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        $category = Category::create([
            'name' => $request->name
        ]);
        return $category;
    }
    public function get_category()
    {
        return Category::orderBy('id', 'desc')->get();
    }
    public function edit_category(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        $category = Category::where('id', $request->id)->update([
            'name' => $request->name
        ]);
        return $category;
    }
    public function delete_category(Request $request)
    {
        $this->validate($request, [
            'id' => 'required'
        ]);
        return Category::where('id', $request->id)->delete();
    }
    // product
    public function get_product()
    {
        $category = Category::all();
        $products = Product::orderBy('id', 'desc')->get();
        return response()->json([
            'products' => $products,
            'category' => $category,
        ], 200);
    }
    // upload product image
    public function upload(Request $request)
    {
        // check image validation
        $this->validate($request, [
            'file' => 'required|mimes:jpg,jpeg,png'
        ]);
        // get image name
        $picName = time() . '.' . $request->file->extension();
        // save it in uploads folder. it locate in public/uploads
        $request->file->move(public_path('uploads/'), $picName);
        return $picName;
    }
    // delete image when upload image
    // make it reuseable
    public function delete_image(Request $request)
    {
        $fileName = $request->imageName;
        $this->deleteCategoryFileFromServer($fileName); // reuse method below
        return 'image deleted';
    }
    public function deleteCategoryFileFromServer($fileName)
    {
        $filePath = public_path('uploads/' . $fileName);
        if (file_exists($filePath)) {
            @unlink($filePath); // @unlink will delete image from uploads folder
        }
        return;
    }
    public function add_product(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'cost' => 'required|numeric',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'category_id' => 'required',
            'image_url' => 'required',
        ]);
        return Product::create([
            'name' => $request->name,
            'cost' => $request->cost,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
            'image_url' => $request->image_url,
            'description' => $request->description,
        ]);
    }
    public function edit_product(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'name' => 'required',
            'cost' => 'required|numeric',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'category_id' => 'required',
            'image_url' => 'required',
        ]);
        return Product::where('id', $request->id)->update([
            'name' => $request->name,
            'cost' => $request->cost,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
            'image_url' => $request->image_url,
            'description' => $request->description,
        ]);
    }
    public function delete_product(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);
        $fileName = $request->image_url;
        $this->deleteCategoryFileFromServer($fileName); // reuse method below
        return Product::where('id', $request->id)->delete();
    }
    public function add_user(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'bail|required|email|unique:users',
            'password' => 'bail|required|min:6',
        ]);
        $password = bcrypt($request->password);
        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $password,
        ]);
    }
    public function get_user(Request $request)
    {
        return User::orderBy('id', 'desc')->get();
    }
    public function edit_user(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            // check email if we not update email make it ignore with user_id
            // but id update it need to be unique
            'email' => "bail|required|email|unique:users,email,$request->id",
            'password' => 'min:6',
        ]);
        $data = [
            'name' => $request->fullName,
            'email' => $request->email,
        ];
        if ($request->password) {
            $password = bcrypt($request->password);
            $data['password'] = $password;
        }
        $user = User::where('id', $request->id)->update($data);
        return $user;
    }
    public function delete_user(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);
        return User::where('id', $request->id)->delete();
    }
    public function get_customer(Request $request)
    {
        return Customer::orderBy('id', 'desc')->get();
    }
    public function delete_customer(Request $request)
    {
        return Customer::where('id', $request->id)->delete();
    }
    public function get_order(Request $request)
    {
        $orders = DB::table('orders')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->leftJoin('payments', 'orders.id', '=', 'payments.order_id')
            ->select(
                'orders.id as order_id',
                'orders.status as order_status',
                'orders.total_price',
                'orders.created_at',
                'customers.id as customer_id',
                'customers.firstname',
                'customers.lastname',
                'customers.email',
                'customers.phone',
                'payments.payment_method',
                'payments.status as payment_status'
            )->orderBy('orders.id', 'desc')
            ->get();

        return response()->json($orders);
    }
    public function get_order_details(Request $request)
    {
        $order_id = $request->order_id;
        // Fetch the products in the order
        $orderDetails = DB::table('order_details')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->select(
                'products.image_url',
                'products.name as product_name',
                'order_details.quantity',
                'order_details.price',
                'order_details.subtotal',
                'order_details.phone as shipping_phone',
                'order_details.address as shipping_address'
            )
            ->where('order_details.order_id', $order_id)
            ->get();

        // Get the first phone and address from the order details
        $shippingDetails = $orderDetails->first();

        return response()->json([
            'products' => $orderDetails,
            'shipping' => [
                'phone' => $shippingDetails->shipping_phone ?? null,
                'address' => $shippingDetails->shipping_address ?? null,
            ],
        ]);
    }
    public function edit_order(Request $request)
    {
        $order_id = $request->order_id;
        Order::where('id', $order_id)->update(['status' => $request->order_status]);
        Payment::where('order_id', $order_id)->update(['status' => $request->payment_status]);
        return response()->json(['message' => 'Order updated successfully!']);
    }
    public function dashboard()
    {
        $products = Product::count();
        $orders = Order::count();
        $customers = Customer::count();
        $adminuser = User::count();
        $income = Order::sum('total_price');
        $expense = Product::sum('cost');
        return response()->json([
            'products' => $products,
            'orders' => $orders,
            'customer' => $customers,
            'adminuser' => $adminuser,
            'income' => $income,
            'expense' => $expense,
        ]);
    }
}
