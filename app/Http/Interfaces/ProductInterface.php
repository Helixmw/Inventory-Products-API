<?php

namespace App\Http\Interfaces;
use Illuminate\Http\Request;


interface ProductInterface{
    public function index();

    public function add(Request $request);

    public function getProduct(Request $request, $id);

    public function editProduct(Request $request, $id);

    public function delete(Request $request, $id);

   


}