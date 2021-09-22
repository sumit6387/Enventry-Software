<?php

namespace App\Http\Controllers;

use App\Functions\AllFunction;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\GST;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\OrderHistoryInvoice;
use App\Models\Product;
use App\Models\User;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PDF;
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
            'gst' => "required",
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
            $product->gst = $request->gst;
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
            'gst' => "required",
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
                $product->gst = $request->gst;
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
            ->where('products.quantity', ">=", 1)
            ->join('brands', 'products.brand', '=', 'brands.id')
            ->join('categories', 'products.category', '=', 'categories.id')
            ->get();
        $data['customers'] = Customer::orderby('id', 'desc')->where('client_id', $email)->get();
        $data['customer_id'] = Order::orderby('id', 'desc')->where('customer', '!=', '')->where('client_id', $email)->where('status', 0)->get()->first();
        $data['customer_detail'] = array();
        if (isset($data['customer_id'])) {
            $data['customer_id'] = $data['customer_id']->customer;
            $data['customer_detail'] = Customer::where('customer_id', $data['customer_id'])->get()->first();
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
                    array_push($arr, array("product_id" => $request->product_id, 'quantity' => 1, 'discount' => 0));
                    $products = json_encode($arr);

                } else {
                    $products = json_encode([array("product_id" => $request->product_id, "quantity" => 1, 'discount' => 0)]);
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
                $no_of_orders = Order::where(['status' => 1, 'client_id' => $request->session()->get('email')])->count();
                if ($no_of_orders < 10) {
                    $order_serial_id = "0000" . ($no_of_orders + 1);
                } else if ($no_of_orders < 100) {
                    $order_serial_id = "000" . ($no_of_orders + 1);
                } else if ($no_of_orders < 1000) {
                    $order_serial_id = "00" . ($no_of_orders + 1);
                } else if ($no_of_orders < 10000) {
                    $order_serial_id = "0" . ($no_of_orders + 1);
                } else if ($no_of_orders < 100000) {
                    $order_serial_id = ($no_of_orders + 1);
                } else {
                    $order_serial_id = "000000" . ($no_of_orders + 1);
                }
                $order->order_id = rand(11111111, 99999999);
                $order->order_serial_id = $order_serial_id;
                $order->client_id = $request->session()->get('email');
                $order->products = json_encode([array("product_id" => $request->product_id, "quantity" => 1, 'discount' => 0)]);
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
            if ($order->products) {
                $order_id = $order->order_id;
            } else {
                $order_id = null;
            }
            if ($order->products == null) {
                return response()->json([
                    'status' => false,
                    'msg' => "No Products Found",
                    'order_id' => $order_id,
                ]);
            }
            $arr = json_decode($order->products);
            $products = array();
            foreach ($arr as $key => $value) {
                $product = Product::select('product_id', 'name', 'price', 'gst')->where('client_id', $email)->where('product_id', $value->product_id)->get()->first();
                $product->product_quantity = $value->quantity;
                array_push($products, $product);
            }
            return response()->json([
                'status' => true,
                'order' => $order,
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
                $product->quantity += $quantity;
                $product->save();
                $data = [];
                foreach ($arr as $key => $value) {
                    array_push($data, $value);
                }
                $order->products = json_encode($data);
                $order->total_amount -= ($product->price * $quantity + ($product->price * $quantity * $product->gst / 100));
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
                                $qt = $value->quantity - $request->quantity;
                                $price = Product::where(['product_id' => $value->product_id, 'client_id' => session('email')])->get()->first();
                                $order->total_amount = ($order->total_amount - ($value->quantity * $price->price + ($value->quantity * $price->price * $price->gst) / 100)) + ($price->price * $request->quantity) + ($price->price * $request->quantity * $price->gst) / 100;
                            } else {
                                $product->quantity = $product->quantity - ($request->quantity - $value->quantity);
                                $qt = ($request->quantity - $value->quantity);
                                $price = Product::where(['product_id' => $value->product_id, 'client_id' => session('email')])->get()->first();
                                $order->total_amount = $order->total_amount + ($price->price * $qt) + (($price->price * $qt * $price->gst) / 100);
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
            if ($request->gst_no) {
                $new->gst_no = $request->gst_no;
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
                    'customer' => $customer,
                ]);
            } else {
                $new = new Order();
                $new->order_id = rand(11111111, 99999999);
                $no_of_orders = Order::where(['status' => 1, 'client_id' => $request->session()->get('email')])->count();
                if ($no_of_orders < 10) {
                    $order_serial_id = "0000" . ($no_of_orders + 1);
                } else if ($no_of_orders < 100) {
                    $order_serial_id = "000" . ($no_of_orders + 1);
                } else if ($no_of_orders < 1000) {
                    $order_serial_id = "00" . ($no_of_orders + 1);
                } else if ($no_of_orders < 10000) {
                    $order_serial_id = "0" . ($no_of_orders + 1);
                } else if ($no_of_orders < 100000) {
                    $order_serial_id = ($no_of_orders + 1);
                } else {
                    $order_serial_id = "000000" . ($no_of_orders + 1);
                }
                $new->order_serial_id = $order_serial_id;
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
            $data['gst'] = GST::where('client_id', $email)->get();
            $ar = [];
            foreach ($arr as $key => $value) {
                $product = Product::select('name', 'price', 'gst')->where('client_id', $email)->where('product_id', $value->product_id)->get()->first();
                $prod = array('product_id' => $value->product_id, 'name' => $product->name, 'price' => $product->price, 'quantity' => $value->quantity, 'gst' => $product->gst, 'product_discount' => $value->discount);
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
            $arr = json_decode($order->products);
            $product = '';
            $noofproduct = 0;
            foreach ($arr as $key => $value) {
                $name = Product::where(['client_id' => $email, 'product_id' => $value->product_id])->get()->first()->name;
                $product = $product . $name . ",";
                $noofproduct++;
            }
            // for generate pdf of bill
            $data['customer'] = Customer::where('customer_id', $order->customer)->where('client_id', $email)->get()->first();
            $arr = json_decode($order->products);
            $data['order'] = $order;
            $data['ordercount'] = Order::where('client_id', $email)->get()->count();
            $data['gst'] = GST::where('client_id', $email)->get();
            $ar = [];
            $str = '';
            foreach ($arr as $key => $value) {
                $product = Product::select('name', 'price', 'gst')->where('client_id', $email)->where('product_id', $value->product_id)->get()->first();
                $prod = array('name' => $product->name, 'price' => $product->price, 'quantity' => $value->quantity, 'gst' => $product->gst);
                array_push($ar, $prod);
                $gst = $product->gst;
                if ($gst <= 0) {
                    $gst = 0;
                }
                $str = $str . $product->name . ",";
                $orderhistory_invoices = new OrderHistoryInvoice();
                $orderhistory_invoices->order_id = $order->order_id;
                $orderhistory_invoices->client_id = $order->client_id;
                $orderhistory_invoices->product_name = $product->name;
                $orderhistory_invoices->discount = $value->discount;
                $orderhistory_invoices->quantity = $value->quantity;
                $orderhistory_invoices->gst = $gst;
                $orderhistory_invoices->price = $product->price;
                $orderhistory_invoices->save();
            }
            $data['products'] = $ar;
            $customer = Customer::where(['customer_id' => $order->customer, "client_id" => $email])->get()->first();
            $orderhistory = new OrderHistory();
            $orderhistory->order_id = $order->order_id;
            $orderhistory->order_serial_id = $order->order_serial_id;
            $orderhistory->client_id = $email;
            $orderhistory->customer_name = $customer->name;
            $orderhistory->customer_mobile_no = $customer->mobile_no;
            $orderhistory->products = $str;
            $orderhistory->noofproduct = $noofproduct;
            $orderhistory->total_amount = $order->total_amount;
            $orderhistory->discount = $order->discount;
            $orderhistory->save();
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
            'company_name' => "required",
            'logo' => "required",
            'mobile_no' => "required",
            'address' => "required",
        ]);

        if ($valid->passes()) {
            $client = User::where('email', $request->email)->get()->first();
            if ($client) {
                return redirect('/clients')->with([
                    'status' => 'danger',
                    'msg' => "This email Already Registered!",
                ]);
            }
            $newClient = new User();
            $newClient->name = $request->name;
            $newClient->email = $request->email;
            $newClient->company_name = $request->company_name;
            $newClient->mobile_no = $request->mobile_no;
            $newClient->address = $request->address;
            $newClient->website = $request->website;
            if ($request->file('logo')) {
                $extension = $request->file('logo')->getClientOriginalExtension();
                $filename = rand(11111111, 999999999) . "." . $extension;
                $path = $request->file('logo')->move(public_path('/client/logo/'), $filename);
                $url1 = url('public/client/logo/' . $filename);
                $newClient->logo = $url1;
            }
            if ($request->gst_no) {
                $newClient->gst_no = $request->gst_no;
            }
            $newClient->password = Hash::make("client@6387");
            $newClient->save();
            $function = new AllFunction();
            $function->sendIdPassword($request->email, 'client@6387');
            return redirect('/clients')->with([
                'status' => 'success',
                'msg' => "Client Added Successfully!!",
            ]);
        } else {
            $error = '';
            foreach ($valid->errors()->all() as $key => $value) {
                $error = $error . $value;
            }
            return redirect('/clients')->with([
                'status' => 'danger',
                'msg' => $error,
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
        $order = OrderHistory::orderby('id', 'desc')->where('client_id', $email)->get();
        return view('orderhistory', ["orders" => $order]);
    }

    public function updateTotalBalance($order_id, $amount, $discount)
    {
        $order = Order::where(['order_id' => $order_id, 'client_id' => session('email')])->get()->first();
        if ($order) {
            $order->total_amount = $amount;
            $order->discount = $discount;
            $order->save();
            return response()->json([
                'status' => true,
            ]);
        } else {
            return response()->json([
                'status' => false,
            ]);
        }
    }

    public function gstonbill(Request $request)
    {
        $gst = GST::orderby('id', 'desc')->where(['client_id' => $request->session()->get('email')])->get();
        return view('gst-on-bill', ['gst' => $gst]);
    }

    public function addgst(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'gst' => "required",
        ]);

        if ($valid->passes()) {
            $gst = new GST();
            $gst->client_id = $request->session()->get('email');
            $gst->gst = $request->gst;
            if ($request->amount) {
                $gst->amount = $request->amount;
            }
            if ($request->condition) {
                $gst->condition = $request->condition;
            }
            $gst->save();
            return response()->json([
                'status' => true,
                'msg' => "GST Added Successfully",
            ]);
        } else {
            return response()->json([
                'status' => false,
                'msg' => $valid->errors()->all(),
            ]);
        }
    }

    public function deleteGST(Request $request, $id)
    {
        $gst = GST::where(['client_id' => $request->session()->get('email'), 'id' => $id])->get()->first();
        if ($gst) {
            $gst->delete();
            return redirect('/gst-on-bill')->with(['status' => "success", 'msg' => "GST Deleted Successfully!!"]);
        } else {
            return redirect('/gst-on-bill')->with(['status' => "danger", 'msg' => "Something Went Wrong!!"]);
        }
    }

    public function balance(Request $request, $balance)
    {
        $order = Order::where(['client_id' => $request->session()->get('email'), 'status' => 0])->get()->first();
        if ($order) {
            $order->total_amount = $balance;
            $order->save();
            return response()->json([
                'status' => true,
            ]);
        } else {
            return response()->json([
                'status' => false,
            ]);
        }
    }

    public function deleteClient($client_id)
    {
        $client = User::where('id', $client_id)->get()->first();
        if ($client) {
            $client->delete();
            return redirect('/clients')->with(['status' => 'success', 'msg' => "Client Deleted Successfully!!"]);
        } else {
            return redirect('/clients')->with(['status' => 'danger', 'msg' => "Something Went Wrong"]);
        }
    }

    public function editClient($client_id)
    {
        $client = User::where('id', $client_id)->get()->first();
        if ($client) {
            return view('editclient', ['client' => $client]);
        } else {
            return redirect('/clients')->with(['status' => 'danger', 'msg' => "Something Went Wrong"]);
        }
    }
    public function editClientProcess(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'name' => "required",
            'email' => "required",
            'company_name' => "required",
            'mobile_no' => "required",
            'address' => "required",
        ]);

        if ($valid->passes()) {
            $client = User::where('id', $request->client_id)->get()->first();
            if ($client) {
                $client->name = $request->name;
                $client->email = $request->email;
                $client->company_name = $request->company_name;
                $client->mobile_no = $request->mobile_no;
                $client->address = $request->address;
                if ($request->website) {
                    $client->website = $request->website;
                }
                if ($request->file('logo')) {
                    $extension = $request->file('logo')->getClientOriginalExtension();
                    $filename = rand(11111111, 999999999) . "." . $extension;
                    $path = $request->file('logo')->move(public_path('/client/logo/'), $filename);
                    $url1 = url('public/client/logo/' . $filename);
                    $client->logo = $url1;
                }
                if ($request->gst_no) {
                    $client->gst_no = $request->gst_no;
                }
                $client->save();
                return redirect('/edit-client/' . $request->client_id)->with(['status' => 'success', 'msg' => "Client Detail Updated Successfully!!"]);
            } else {
                return redirect('/edit-client/' . $request->client_id)->with(['status' => 'danger', 'msg' => "Something Went Wrong"]);
            }
        } else {
            $error = '';
            foreach ($valid->errors()->all() as $key => $value) {
                $error = $error . $value;
            }
            return redirect('/edit-client/' . $request->client_id)->with(['status' => "danger", 'msg' => $error]);
        }
    }

    public function todayOrderHistory()
    {
        $function = new AllFunction();
        $users = User::where('role', "Client")->get();
        foreach ($users as $key => $value) {
            $function->todayHistoryEmail($value->email);
        }
    }

    public function viewInvoice(Request $request, $order_id)
    {
        $email = $request->session()->get('email');
        $order = Order::where(['client_id' => $email, 'order_id' => $order_id])->get()->first();
        if ($order) {
            $data['customer'] = Customer::where('customer_id', $order->customer)->where('client_id', $email)->get()->first();
            $ar = OrderHistoryInvoice::where(['client_id' => $email, 'order_id' => $order_id])->get();
            if (count($ar) <= 0) {
                return redirect('/orderHistory')->with(['status' => 'danger', 'msg' => "There No Record Related This Order."]);
            }
            $data['order'] = $order;
            $data['ordercount'] = Order::where('client_id', $email)->get()->count();
            $data['gst'] = GST::where('client_id', $email)->get();
            $arr = [];
            foreach ($ar as $key => $value) {
                $prod = array('name' => $value->product_name, 'price' => $value->price, 'quantity' => $value->quantity, 'gst' => $value->gst, 'product_discount' => $value->discount);
                array_push($arr, $prod);
            }
            $data['products'] = $arr;
            return view('view-invoice', $data);
        } else {
            return redirect('/orderHistory');
        }
    }

    public function test(Request $request)
    {
        date_default_timezone_set('Asia/Kolkata');
        $valid = Validator::make($request->all(), [
            'from' => "required",
            'to' => "required",
        ]);
        if ($valid->passes()) {
            $email = session('email');
            $from = date('d-m-y h:i:s', strtotime(date('2021-09-06 09:41:23')));
            $to = date('d-m-y h:i:s');
            // dd($from . "  " . $to);
            $from = date('y-m-d', strtotime($request->from)) . " 00:00:00";
            $to = date('y-m-d', strtotime($request->to)) . " 00:00:00";
            if (strtotime($from) == strtotime($to)) {
                $orders = Order::where(['client_id' => $email, 'status' => 1])->whereDate('updated_at', '=', $from)->get();
            } else {
                $orders = Order::where(['client_id' => $email, 'status' => 1])->whereBetween('updated_at', [$from, $to])->get();
            }
            if (count($orders) == 0) {
                return redirect('/orderHistory')->with(['status' => "danger", 'msg' => "No Data Found Between These Date."]);
            }
            $data = [];
            foreach ($orders as $key => $value) {
                $orderhistoryinvoice = OrderHistoryInvoice::where(['client_id' => $email, 'order_id' => $value->order_id])->get();
                $customer = Customer::where(['customer_id' => $value->customer, 'client_id' => $email])->get()->first();
                $orderhistory = OrderHistory::where(['client_id' => $email, 'order_id' => $value->order_id])->get()->first();
                if (count($orderhistoryinvoice) && $orderhistory) {
                    $ar = [];
                    foreach ($orderhistoryinvoice as $invoicedata) {
                        $a = ['name' => $invoicedata->product_name, 'quantity' => $invoicedata->quantity, 'gst' => $invoicedata->gst, 'price' => $invoicedata->price, 'product_discount' => $invoicedata->discount];
                        array_push($ar, $a);
                    }
                    $arr = ["invoice_no" => $orderhistory->order_serial_id, 'customer_name' => $customer->name, 'customer_no' => $customer->mobile_no, 'discount' => $orderhistory->discount, 'products' => $ar, 'total_amount' => $orderhistory->total_amount];
                    array_push($data, $arr);
                }

            }
            // dd($data);
            $pdf = PDF::loadView('emails.test', ['data' => $data, 'from' => $request->from, 'to' => $request->to]);
            return $pdf->download('history.pdf');
        } else {
            $error = '';
            foreach ($valid->errors()->all() as $key => $value) {
                $error = $error . $value;
            }
            return redirect('/orderHistory')->with(['status' => "danger", 'msg' => $error]);
        }

    }

    public function showCustomers()
    {
        $email = session('email');
        $data['customers'] = Customer::orderby('id', 'desc')->where(['client_id' => $email])->get();
        return view('customers', $data);
    }

    public function editCustomer($customer_id)
    {
        $email = session('email');
        $customer = Customer::where(['client_id' => $email, 'customer_id' => $customer_id])->get()->first();
        if ($customer) {
            return view('edit-customer', ['customer' => $customer]);
        } else {
            return redirect('/customers');
        }
    }

    public function discountOnProduct($orderid, $product_id, $discount)
    {
        $email = session('email');
        $order = Order::where(['client_id' => $email, 'order_id' => $orderid])->get()->first();
        if ($order) {
            $arr = json_decode($order->products);
            $ar = array();
            foreach ($arr as $value) {
                if ($value->product_id == $product_id) {
                    $value->discount = $discount;
                }
                array_push($ar, $value);
            }
            $order->products = json_encode($ar);
            $order->save();
            return response()->json([
                'status' => true,
                'msg' => "Discount Added",
            ]);
        } else {
            return response()->json([
                'status' => false,
                'msg' => "Something Went Wrong!",
            ]);
        }
    }

    public function updateCustomer(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'name' => "required",
        ]);
        if ($valid->passes()) {
            $email = session('email');
            $customer = Customer::where(['client_id' => $email, 'customer_id' => $request->customer_id])->get()->first();
            if ($customer) {
                $customer->name = $request->name;
                $customer->email = $request->email;
                $customer->mobile_no = $request->mobile_no;
                $customer->address = $request->address;
                $customer->pincode = $request->pincode;
                $customer->gst_no = $request->gst_no;
                $customer->update();
                // dd($customer);
                return redirect('/edit-customer/' . $request->customer_id)->with(['status' => "success", 'msg' => "Customer Updated Successfully"]);
            } else {
                return redirect('/edit-customer/' . $request->customer_id)->with(['status' => "danger", 'msg' => "Something Went Wrong!"]);
            }
        } else {
            $error = '';
            foreach ($valid->errors()->all() as $key => $value) {
                $error = $error . $value;
            }
            return redirect('/edit-customer/' . $request->customer_id)->with(['status' => "danger", 'msg' => $error]);
        }
    }

}
