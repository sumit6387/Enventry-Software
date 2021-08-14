<?php

namespace App\Http\Controllers;

use App\Functions\AllFunction;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Validator;

class AdminController extends Controller
{
    public function brand(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'brand_name' => "required",
        ]);
        if ($valid->passes()) {
            $newbrand = new Brand();
            $newbrand->brand_name = $request->brand_name;
            $newbrand->client_id = $request->session()->get('email');
            $newbrand->save();
            return response()->json([
                'status' => true,
                'msg' => "Brand Added Successfully",
            ]);
        } else {
            return response()->json([
                'status' => false,
                'msg' => $valid->errors()->all(),
            ]);
        }
    }

    public function showbrand(Request $request)
    {
        $email = $request->session()->get('email');
        $brands = Brand::orderby('id', 'desc')->where('client_id', $email)->get();
        return view('brand', ['brands' => $brands]);
    }

    public function deletebrand(Request $request, $id)
    {
        $email = $request->session()->get('email');
        $brand = Brand::where('id', $id)->where('client_id', $email)->get()->first();
        if ($brand) {
            $brand->delete();
            return redirect('/brands')->with(['status' => "success", 'msg' => "Brand Deleted Successfully"]);
        } else {
            return redirect('/brands')->with(['status' => "danger", 'msg' => "Something Went Wrong"]);
        }
    }

    public function editbrand(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'brand_name' => "required",
        ]);

        if ($valid->passes()) {
            $email = $request->session()->get('email');
            $brand = Brand::where('id', $request->brand_id)->where('client_id', $email)->get()->first();
            if ($brand) {
                $brand->brand_name = $request->brand_name;
                $brand->save();
                return redirect('/editbrand/' . $request->brand_id)->with([
                    'status' => 'success',
                    'msg' => "Brand Updated Successfully!!",
                ]);
            } else {
                return redirect('/editbrand/' . $request->brand_id)->with([
                    'status' => 'danger',
                    'msg' => "Something Went Wrong!!",
                ]);
            }
        } else {
            return redirect('/editbrand/' . $request->brand_id)->with([
                'status' => 'danger',
                'msg' => $valid->errors()->all(),
            ]);
        }
    }

    public function showeditbrand(Request $request, $id)
    {
        $email = $request->session()->get('email');
        $brand = Brand::where('id', $id)->where('client_id', $email)->get()->first();
        if ($brand) {
            return view('editbrand', ['brand' => $brand]);
        } else {
            return redirect('/brands')->with([
                'status' => 'danger',
                'msg' => "Something Went Wrong",
            ]);
        }
    }

    public function category(Request $request)
    {
        $email = $request->session()->get('email');
        $data['brands'] = Brand::orderby('id', 'desc')->where('client_id', $email)->get();
        $data['categories'] = Category::select(['categories.*', 'brands.brand_name'])->where('categories.client_id', $email)->join('brands', 'categories.brand_id', '=', 'brands.id')->get();
        return view('category', $data);
    }

    public function addcategory(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'category' => "required",
        ]);
        if ($valid->passes()) {
            $newCategory = new Category();
            $newCategory->category = $request->category;
            $newCategory->brand_id = $request->brand;
            $newCategory->client_id = $request->session()->get('email');
            $newCategory->save();
            return response()->json([
                'status' => true,
                'msg' => "Category Added Successfully!!",
            ]);
        } else {
            return \response()->json([
                'status' => false,
                'msg' => $valid->errors()->all(),
            ]);
        }
    }

    public function deletecategory(Request $request, $id)
    {
        $email = $request->session()->get('email');
        $category = Category::where('id', $id)->where('client_id', $email)->get()->first();
        if ($category) {
            $category->delete();
            return redirect('/category')->with(['status' => 'success', 'msg' => "Category Deleted Successfully!!"]);
        } else {
            return redirect('/category')->with(['status' => 'danger', 'msg' => "Something Went Wrong!!"]);
        }
    }

    public function editcategory(Request $request, $id)
    {
        $email = $request->session()->get('email');
        $data['category'] = Category::where('id', $id)->where('client_id', $email)->get()->first();
        $data['brands'] = Brand::orderby('id', 'desc')->where('client_id', $email)->get();
        return view('editcategory', $data);
    }

    public function updatecategory(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'category' => "required",
        ]);
        if ($valid->passes()) {
            $email = $request->session()->get('email');
            $category = Category::where('id', $request->id)->where('client_id', $email)->get()->first();
            if ($category) {
                $category->category = $request->category;
                $category->brand_id = $request->brand;
                $category->save();
                return redirect('/editcategory/' . $request->id)->with(['status' => 'success', 'msg' => 'Category Updated Successfully!!']);
            } else {
                return redirect('/editcategory/' . $request->id)->with(['status' => 'danger', 'msg' => 'Something Went Wrong!!']);
            }
        } else {
            return redirect('/editcategory/' . $request->id)->with(['status' => 'danger', 'msg' => $valid->errors()->all()]);
        }
    }

    public function products(Request $request)
    {
        $email = $request->session()->get('email');
        $data['brands'] = Brand::orderby('id', 'desc')->where('client_id', $email)->get();
        $data['products'] = Product::select(['categories.category as category_name', 'brands.brand_name', 'products.*'])
            ->where('products.client_id', $email)
            ->join('brands', 'products.brand', '=', 'brands.id')
            ->join('categories', 'products.category', '=', 'categories.id')
            ->get();
        return view('products', $data);
    }

    public function getCategory(Request $request, $id)
    {
        $email = $request->session()->get('email');
        $category = Category::where('brand_id', $id)->where('client_id', $email)->get();
        if (count($category)) {
            return response()->json([
                'status' => true,
                'data' => $category,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'msg' => "No Data Found",
            ]);
        }
    }

    public function addProduct(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'name' => "required",
            'brand' => "required",
            'category' => "required",
            'price' => "required",
            'quantity' => "required",
        ]);

        if ($valid->passes()) {
            $product = new Product();
            $product->product_id = rand(11111, 99999);
            $product->name = $request->name;
            $product->client_id = $request->session()->get('email');
            $product->brand = $request->brand;
            $product->category = $request->category;
            $product->price = $request->price;
            $product->quantity = $request->quantity;
            $product->save();
            return response()->json([
                'status' => true,
                'msg' => "Product Added Successfully!!",
            ]);
        } else {
            return response()->json([
                'status' => false,
                'msg' => $valid->errors()->all(),
            ]);
        }
    }

    public function deleteproduct(Request $request, $id)
    {
        $email = $request->session()->get('email');
        $product = Product::where('product_id', $id)->where('client_id', $email)->get()->first();
        if ($product) {
            $product->delete();
            return redirect('/products')->with([
                'status' => "success",
                'msg' => "Product Removed Successfully!!",
            ]);
        } else {
            return redirect('/products')->with([
                'status' => 'danger',
                'msg' => "Product Not Found!",
            ]);
        }
    }

    public function editproduct(Request $request, $id)
    {
        $email = $request->session()->get('email');
        $data['product'] = Product::where('product_id', $id)->where('client_id', $email)->get()->first();
        $data['brands'] = Brand::orderby('id', 'desc')->where('client_id', $email)->get();
        $data['category'] = Category::where('brand_id', $data['product']->brand)->where('client_id', $email)->get();
        if ($data['product']) {
            return view('editproduct', $data);
        } else {
            return redirect('/products')->with([
                'status' => 'danger',
                'msg' => "Something Went Wrong!!",
            ]);
        }
    }

    public function updateproduct(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'name' => "required",
            'brand' => "required",
            'category' => "required",
            'price' => "required",
            'quantity' => "required",
        ]);

        if ($valid->passes()) {
            $email = $request->session()->get('email');
            $product = Product::where('product_id', $request->product_id)->where('client_id', $email)->get()->first();
            if ($product) {
                $product->name = $request->name;
                $product->brand = $request->brand;
                $product->category = $request->category;
                $product->price = $request->price;
                $product->quantity = $request->quantity;
                $product->save();
                return redirect('/editproduct/' . $request->product_id)->with([
                    'status' => "success",
                    'msg' => "Product Updated Successfully!!",
                ]);
            } else {
                return redirect('/editproduct/' . $request->product_id)->with([
                    'status' => 'danger',
                    'msg' => "Something Went Wrong!!",
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'msg' => $valid->errors()->all(),
            ]);
        }
    }

    public function order(Request $request)
    {
        $email = $request->session()->get('email');
        $data['products'] = Product::select(['categories.category as category_name', 'brands.brand_name', 'products.*'])
            ->where('products.client_id', $email)
            ->join('brands', 'products.brand', '=', 'brands.id')
            ->join('categories', 'products.category', '=', 'categories.id')
            ->get();
        $data['customers'] = Customer::orderby('id', 'desc')->where('client_id', $email)->get();
        $data['customer_id'] = Order::orderby('id', 'desc')->where('client_id', $email)->where('status', 0)->get()->first();
        if ($data['customer_id']) {
            $data['customer_id'] = $data['customer_id']->customer;
        } else {
            $data['customer_id'] = "";
        }
        return view('order', $data);
    }

    public function addItem(Request $request)
    {
        date_default_timezone_set("Asia/Kolkata");
        $valid = Validator::make($request->all(), [
            'product_id' => "required",
        ]);
        if ($valid->passes()) {
            $email = $request->session()->get('email');
            $order = Order::orderby('id', 'desc')->where('client_id', $email)->where('status', 0)->get()->first();
            if ($order) {
                if ($order->products != null) {
                    $arr = json_decode($order->products);
                    foreach ($arr as $key => $value) {
                        if ($value->product_id == $request->product_id) {
                            return response()->json([
                                'status' => false,
                                'msg' => "This product already added. increase qunatity",
                            ]);
                        }
                    }
                    array_push($arr, array("product_id" => $request->product_id, 'quantity' => 1));
                    $products = json_encode($arr);

                } else {
                    $products = json_encode([array("product_id" => $request->product_id, "quantity" => 1)]);
                }
                $order->products = $products;
                $product = Product::where('product_id', $request->product_id)->where('client_id', $email)->get()->first();
                $product->quantity -= 1;
                $product->save();
                $order->total_amount += $product->price;
                $order->save();
                return response()->json([
                    'status' => true,
                ]);
            } else {
                $order = new Order();
                $order->order_id = Str::upper(Str::random(10));
                $order->products = json_encode([array("product_id" => $request->product_id, "quantity" => 1)]);
                $product = Product::where('product_id', $request->product_id)->where('client_id', $email)->get()->first();
                $product->quantity -= 1;
                $product->save();
                $order->total_amount = $product->price;
                $order->save();
                return response()->json([
                    'status' => true,
                    'msg' => "Product Added Successfully!!",
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'msg' => $valid->errors()->all(),
            ]);
        }
    }

    public function getorders(Request $request)
    {
        $email = $request->session()->get('email');
        $order = Order::orderby('id', 'desc')->where('client_id', $email)->where('status', 0)->get()->first();
        if ($order) {
            if ($order->products == null) {
                return response()->json([
                    'status' => false,
                    'msg' => "No Products Found",
                    'order_id' => $order->order_id,
                ]);
            }
            $arr = json_decode($order->products);
            $products = array();
            foreach ($arr as $key => $value) {
                $product = Product::select('product_id', 'name', 'price')->where('client_id', $email)->where('product_id', $value->product_id)->get()->first();
                $product->product_quantity = $value->quantity;
                array_push($products, $product);
            }
            return response()->json([
                'status' => true,
                'data' => $products,
                'order_id' => $order->order_id,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'msg' => "Add Products",
            ]);
        }
    }

    public function removeItem(Request $request, $product_id)
    {
        date_default_timezone_set("Asia/Kolkata");
        $email = $request->session()->get('email');
        $product = Product::where('product_id', $product_id)->where('client_id', $email)->get()->first();
        if ($product) {
            $order = Order::orderby('id', 'desc')->where('client_id', $email)->where('status', 0)->get()->first();
            if ($order) {
                $arr = json_decode($order->products);
                foreach ($arr as $key => $value) {
                    if ($value->product_id == $product_id) {
                        unset($arr[$key]);
                        $quantity = $value->quantity;
                    }
                }
                $data = [];
                foreach ($arr as $key => $value) {
                    array_push($data, $value);
                }
                $order->products = json_encode($data);
                $order->total_amount -= $product->price * $quantity;
                $order->save();
                return response()->json([
                    'status' => true,
                    'msg' => "Item Deleted Successfully!!",
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'msg' => "Something Went Wrong!!",
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'msg' => "Something Went Wrong!!",
            ]);
        }
    }

    public function changeQuantity(Request $request)
    {
        date_default_timezone_set("Asia/Kolkata");
        $valid = Validator::make($request->all(), [
            'product_id' => "required",
            "quantity" => "required",
        ]);

        if ($valid->passes()) {
            $email = $request->session()->get('email');
            $product = Product::where('product_id', $request->product_id)->where('client_id', $email)->get()->first();
            if ($product) {
                $order = Order::orderby('id', 'desc')->where('client_id', $email)->where('status', 0)->get()->first();
                if ($product->quantity < $request->quantity) {
                    return response()->json([
                        'status' => false,
                        'msg' => "Your stock is less then quantity you entered.",
                    ]);
                }
                if ($order) {
                    $arr = json_decode($order->products);
                    foreach ($arr as $key => $value) {
                        if ($value->product_id == $request->product_id) {
                            if ($value->quantity > $request->quantity) {
                                $product->quantity = $product->quantity + ($value->quantity - $request->quantity);
                            } else {
                                $product->quantity = $product->quantity - ($request->quantity - $value->quantity);
                            }
                            $product->save();
                            $value->quantity = $request->quantity;
                        }
                    }
                    $order->products = json_encode($arr);
                    $order->save();
                    return response()->json([
                        'status' => true,
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'msg' => "Something Went Wrong",
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'msg' => "Something Went Wrong",
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'msg' => $valid->errors()->all(),
            ]);
        }
    }

    public function addCustomer(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'name' => "required",
        ]);

        if ($valid->passes()) {
            $email = $request->session()->get('email');
            $new = new Customer();
            $new->customer_id = Str::upper(Str::random(10));
            $new->name = $request->name;
            $new->client_id = $email;
            if ($request->email) {
                $new->email = $request->email;
            }
            if ($request->mobile_no) {
                $new->mobile_no = $request->mobile_no;
            }
            if ($request->address) {
                $new->address = $request->address;
            }
            if ($request->pincode) {
                $new->pincode = $request->pincode;
            }
            $new->save();
            return response()->json([
                'status' => true,
                'msg' => "Customer Added Successfully!",
            ]);
        } else {
            return response()->json([
                'status' => false,
                'msg' => $valid->errors()->all(),
            ]);
        }
    }

    public function addCustomerToOrder(Request $request, $customer_id)
    {
        date_default_timezone_set("Asia/Kolkata");
        $email = $request->session()->get('email');
        $customer = Customer::where('customer_id', $customer_id)->where('client_id', $email)->get()->first();
        if ($customer) {
            $order = Order::orderby('id', 'desc')->where('client_id', $email)->where('status', 0)->get()->first();
            if ($order) {
                $order->customer = $customer_id;
                $order->save();
                return response()->json([
                    'status' => true,
                    'msg' => "Customer Added!!",
                ]);
            } else {
                $new = new Order();
                $new->order_id = Str::upper(Str::random(10));
                $new->customer = $customer_id;
                $new->client_id = $email;
                $new->save();
                return response()->json([
                    'status' => true,
                    'msg' => "Customer Added!!",
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'msg' => "Something Went Wrong!!",
            ]);
        }
    }

    public function invoice(Request $request, $order_id)
    {
        $email = $request->session()->get('email');
        $order = Order::where('order_id', $order_id)->where('client_id', $email)->get()->first();
        if ($order) {
            $data['customer'] = Customer::where('customer_id', $order->customer)->where('client_id', $email)->get()->first();
            $arr = json_decode($order->products);
            $data['order'] = $order;
            $data['ordercount'] = Order::where('client_id', $email)->get()->count();
            $ar = [];
            foreach ($arr as $key => $value) {
                $product = Product::select('name', 'price')->where('client_id', $email)->where('product_id', $value->product_id)->get()->first();
                $prod = array('name' => $product->name, 'price' => $product->price, 'quantity' => $value->quantity);
                array_push($ar, $prod);
            }
            $data['products'] = $ar;
            return view('invoice', $data);
        } else {
            return redirect('/orders');
        }
    }

    public function index(Request $request)
    {
        $email = $request->session()->get('email');
        $data['customers'] = Customer::where('client_id', $email)->get()->count();
        $data['orders'] = Order::where('client_id', $email)->get()->count();
        $data['brands'] = Brand::where('client_id', $email)->get()->count();
        $data['productNO'] = Product::where('client_id', $email)->get()->count();
        $data['products'] = $data['products'] = Product::select(['categories.category as category_name', 'brands.brand_name', 'products.*'])
            ->where('products.client_id', $email)
            ->orderby('products.id', 'desc')
            ->join('brands', 'products.brand', '=', 'brands.id')
            ->join('categories', 'products.category', '=', 'categories.id')
            ->get();
        return view('index', $data);
    }

    public function changeStatusOfOrder(Request $request)
    {
        $email = $request->session()->get('email');
        $order = Order::orderby('id', 'desc')->where('client_id', $email)->where('status', 0)->get()->first();
        if ($order) {
            $order->status = 1;
            $order->save();
            return response()->json([
                'status' => true,
            ]);
        }
    }

    public function addclient(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'name' => "required",
            'email' => "required",
        ]);

        if ($valid->passes()) {
            $client = User::where('email', $request->email)->get()->first();
            if ($client) {
                return response()->json([
                    'status' => false,
                    'msg' => "This email Already Registered!",
                ]);
            }
            $newClient = new User();
            $newClient->name = $request->name;
            $newClient->email = $request->email;
            $newClient->password = Hash::make("client@6387");
            $newClient->save();
            $function = new AllFunction();
            $function->sendIdPassword($request->email, 'client@6387');
            return response()->json([
                'status' => true,
                'msg' => "Client Added Successfully!!",
            ]);
        } else {
            return response()->json([
                'status' => false,
                'msg' => $valid->errors()->all(),
            ]);
        }
    }

    public function clients()
    {
        $data['clients'] = User::orderby('id', 'desc')->where(['role' => "Client"])->get();
        return view('clients', $data);
    }

    public function orderHistory(Request $request)
    {
        $email = $request->session()->get('email');
        $order = Order::orderby('id', 'desc')->where('client_id', $email)->get();
        $products = array();
        foreach ($order as $key => $value) {
            $ar = json_decode($value->products);
            $value->customer_name = Customer::select('name')->where('customer_id', $value->customer)->where('client_id', $email)->get()->first()->name;
            foreach ($ar as $key => $prod) {
                $product = ["name" => Product::select('name')->where('product_id', $prod->product_id)->where('client_id', $email)->get()->first()->name];
                \array_push($products, $product);
            }
            $value->product_list = $products;
        }
        return view('orderhistory', ["orders" => $order]);
    }

}
