<?php

namespace App\Http\Interfaces;

Interface RepoInterface{
    public function index();
    public function create(array $data);
    public function update(array $data , $id);
    public function delete($id);
    public function show($id);

}