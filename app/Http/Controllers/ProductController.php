<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Resources\ProductCollection;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    use ApiResponse;
    /**
     * this function will return product list
     *
     * @return mixed
     */
    public function list(){
        try {

            $products =  ProductCollection::collection(Product::all());
            $products = json_decode($products->toJson(), true);
            return $this->success($products, 200);

        }catch (ErrorException $exception){
            return $this->error($exception->getMessage(), $exception->getCode());
        }
    }

    public function show($id){
        try{
            $product = Product::find($id);
            $product = new ProductCollection($product);
            $products = json_decode($product->toJson(), true);
            return $this->success($products, 200);

        }catch (ErrorException $exception){
            return $this->error($exception->getMessage(), $exception->getCode());
        }
    }


    /**
     * create new product
     *
     * @return mixed
     */
    public function create(Request $request){
        $product =  new Product();
        $image = '';
        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $destination_path = 'images/product';
            $image = Storage::disk('public')->put($destination_path, $image, 'public');
        }

        $product = $product->create([
            'title' => $request->title,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $image
        ]);
        if ($product){
            $product = new ProductCollection($product);
            $product = json_decode($product->toJson(), true);
           return $this->success($product, 200);

        }
        return $this->error('', 500);
    }


    /**
     * edit existing product product
     *
     * @return mixed
     */
    public function edit(Request $request, $id){
        $product = Product::find($id);
        $image = $product->image;
        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $destination_path = 'images/product';
            $image = Storage::disk('public')->put($destination_path, $image, 'public');
        }

        $product->title = $request->title;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->image = $image;

        if ($product->save()){
            $product = new ProductCollection($product);
            $product = json_decode($product->toJson(), true);
            return $this->success($product, 200);

        }
        return $this->error('', 500);

    }

    public function destroy($id){
        $product = Product::find($id);
        if ($product){
            $image = $product->image;
            if ($product->delete()){
                if (\Storage::disk('public')->exists($image))
                    \Storage::disk('public')->delete($image);

                return $this->success([], 200, 'Product has been deleted');
            }

        }
        return $this->error([], 500);
    }
}
