<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Kategori;

class CategoryController extends Controller
{
    public function index(){
        $rset_Category = Kategori::all();
        // echo $rset_Category[0] -> deskripsi;
        // return $rset_Category[1] -> deskripsi;

        return view("category.index", compact('rset_Category'));
        // return view("category.master");
    }
}
