@extends('layouts.admin')
@section('title', 'Edit Product')
@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-3xl">
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.products._form', ['product' => $product])
    </form>
    @if($product->images->count())
    <div class="flex flex-wrap gap-2 mt-4">
        @foreach($product->images as $img)
            <div class="relative">
                @if($img->is_video)
                    <video src="{{ Storage::url($img->image) }}" class="w-16 h-16 object-cover rounded border" muted></video>
                    <span class="absolute bottom-0 right-0 bg-black/70 text-white text-[10px] px-1 rounded-tl">▶</span>
                @else
                    <img src="{{ Storage::url($img->image) }}" class="w-16 h-16 object-cover rounded border" alt="Product media">
                @endif
            </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
