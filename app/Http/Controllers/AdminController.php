<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
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

    public function showbrand()
    {
        $brands = Brand::orderby('id', 'desc')->get();
        return view('brand', ['brands' => $brands]);
    }

    public function deletebrand($id)
    {
        $brand = Brand::where('id', $id)->get()->first();
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
            $brand = Brand::where('id', $request->brand_id)->get()->first();
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

    public function showeditbrand($id)
    {
        $brand = Brand::where('id', $id)->get()->first();
        if ($brand) {
            return view('editbrand', ['brand' => $brand]);
        } else {
            return redirect('/brands')->with([
                'status' => 'danger',
                'msg' => "Something Went Wrong",
            ]);
        }
    }

    public function category()
    {
        $data['brands'] = Brand::orderby('id', 'desc')->get();
        $data['categories'] = Category::select(['categories.*', 'brands.brand_name'])->join('brands', 'categories.brand_id', '=', 'brands.id')->get();
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

    public function deletecategory($id)
    {
        $category = Category::where('id', $id)->get()->first();
        if ($category) {
            $category->delete();
            return redirect('/category')->with(['status' => 'success', 'msg' => "Category Deleted Successfully!!"]);
        } else {
            return redirect('/category')->with(['status' => 'danger', 'msg' => "Something Went Wrong!!"]);
        }
    }

    public function editcategory($id)
    {
        $data['category'] = Category::where('id', $id)->get()->first();
        $data['brands'] = Brand::orderby('id', 'desc')->get();
        return view('editcategory', $data);
    }

    public function updatecategory(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'category' => "required",
        ]);
        if ($valid->passes()) {
            $category = Category::where('id', $request->id)->get()->first();
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

    public function products()
    {
        $data['brands'] = Brand::orderby('id', 'desc')->get();
        $data['products'] = Product::select(['categories.category as category_name', 'brands.brand_name', 'products.*'])
            ->join('brands', 'products.brand', '=', 'brands.id')
            ->join('categories', 'products.category', '=', 'categories.id')
            ->get();
        return view('products', $data);
    }

    public function getCategory($id)
    {
        $category = Category::where('brand_id', $id)->get();
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

    public function deleteproduct($id)
    {
        $product = Product::where('product_id', $id)->get()->first();
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

    public function editproduct($id)
    {
        $data['product'] = Product::where('product_id', $id)->get()->first();
        $data['brands'] = Brand::orderby('id', 'desc')->get();
        $data['category'] = Category::where('brand_id', $data['product']->brand)->get();
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
            $product = Product::where('product_id', $request->product_id)->get()->first();
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

    public function order()
    {
        $data['products'] = Product::select(['categories.category as category_name', 'brands.brand_name', 'products.*'])
            ->join('brands', 'products.brand', '=', 'brands.id')
            ->join('categories', 'products.category', '=', 'categories.id')
            ->get();
        $data['customers'] = Customer::orderby('id', 'desc')->get();
        $data['customer_id'] = Order::orderby('id', 'desc')->where('status', 0)->get()->first()->customer;
        return view('order', $data);
    }

    public function addItem(Request $request)
    {
        date_default_timezone_set("Asia/Kolkata");
        $valid = Validator::make($request->all(), [
            'product_id' => "required",
        ]);
        if ($valid->passes()) {
            $order = Order::orderby('id', 'desc')->where('status', 0)->get()->first();
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
                $product = Product::where('product_id', $request->product_id)->get()->first();
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
                $product = Product::where('product_id', $request->product_id)->get()->first();
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

    public function getorders()
    {
        $order = Order::orderby('id', 'desc')->where('status', 0)->get()->first();
        if ($order) {
            if ($order->products == null) {
                return response()->json([
                    'status' => false,
                    'msg' => "No Products Found",
                ]);
            }
            $arr = json_decode($order->products);
            $products = array();
            foreach ($arr as $key => $value) {
                $product = Product::select('product_id', 'name', 'price')->where('product_id', $value->product_id)->get()->first();
                $product->product_quantity = $value->quantity;
                array_push($products, $product);
            }
            return response()->json([
                'status' => true,
                'data' => $products,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'msg' => "Add Products",
            ]);
        }
    }

    public function removeItem($product_id)
    {
        date_default_timezone_set("Asia/Kolkata");
        $product = Product::where('product_id', $product_id)->get()->first();
        if ($product) {
            $order = Order::orderby('id', 'desc')->where('status', 0)->get()->first();
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
            $product = Product::where('product_id', $request->product_id)->get()->first();
            if ($product) {
                $order = Order::orderby('id', 'desc')->where('status', 0)->get()->first();
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
            $new = new Customer();
            $new->customer_id = Str::upper(Str::random(10));
            $new->name = $request->name;
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

    public function addCustomerToOrder($customer_id)
    {
        date_default_timezone_set("Asia/Kolkata");
        $customer = Customer::where('customer_id', $customer_id)->get()->first();
        if ($customer) {
            $order = Order::orderby('id', 'desc')->where('status', 0)->get()->first();
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

    public function invoice($order_id)
    {
        $order = Order::where('order_id', $order_id)->get()->first();
        if ($order) {
            $data['customer'] = Customer::where('customer_id', $order->customer)->get()->first();
            $arr = json_decode($order->products);
            $data['order'] = $order;
            $data['ordercount'] = Order::get()->count();
            $ar = [];
            foreach ($arr as $key => $value) {
                $product = Product::select('name', 'price')->where('product_id', $value->product_id)->get()->first();
                $prod = array('name' => $product->name, 'price' => $product->price, 'quantity' => $value->quantity);
                array_push($ar, $prod);
            }
            $data['products'] = $ar;
            $order->status = 1;
            $order->save();
            return view('invoice', $data);
        } else {
            return redirect('/orders');
        }
    }

}
