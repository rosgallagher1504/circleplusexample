<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function AllCat(){



        $categories = Category::latest()->paginate(5); //use Category::all(); order by id
        $trachCat = Category::onlyTrashed()->latest()->paginate(2);


       
        //$categories = DB::table('categories')->latest()->paginate(5);
        return view('admin.category.index', compact('categories', 'trachCat'));
    }


     public function AddCat(Request $request){
     
     $validatedData = $request->validate([
         'category_name' => 'required|unique:categories|max:225',
     ],
     [
        'category_name.required' => 'Please Input Category Name',
        'category_name.max' => 'Category should be less than 225 chars',

     ]);

    

    //ORM Insert
    //  Category::insert([
    //      'category_name' => $request->category_name,
    //      'user_id'=> Auth::user()->id,
    //      'created_at'=> Carbon::now()
    //  ]);

       //Better ORM insert using model
        $category = new Category; 
        $category->category_name = $request->category_name;
        $category->user_id = Auth::user()->id;
        $category->save();

        //Inser data using query builder using db directly
        // $data = array();
        // $data['category_name']= $request->category_name;
        // $data['user_id'] =Auth::user()->id;
        // DB::table('categories')->insert($data);

        return Redirect()->back()->with('success','Category Inserted Successfully');

    }

    public function Edit($id){
        // $categories = Category::find($id); 
        $categories = DB::table('categories')->where('id',$id)->first();
        return view('admin.category.edit', compact('categories'));

    }


    public function Update(Request $request ,$id){
       // $update = Category::find($id)->update([
       //     'category_name' => $request->category_name,
       //     'user_id' => Auth::user()->id 

       //  ]);

       $data = array();
       $data['category_name'] = $request->category_name;
       $data['user_id'] = Auth::user()->id;
       DB::table('categories')->where('id',$id)->update($data);

       return Redirect()->route('all.category')->with('success', 'Category Updated Successfully');


    }




}