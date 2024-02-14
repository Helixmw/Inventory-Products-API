<?php

namespace App\Http\Interfaces;
interface ProductInterface{
    public function index();

    public function add(Request $request);

    public function getProduct(Request $request, $id);

    public function editProduct(Request $request, $id);

    public function delete(Request $request, $id);

   


}