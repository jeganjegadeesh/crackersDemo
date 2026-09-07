@extends('layouts.admin')
@section('title', 'Add Product')
@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-3xl">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.products._form', ['product' => null])
    </form>
</div>
@endsection
