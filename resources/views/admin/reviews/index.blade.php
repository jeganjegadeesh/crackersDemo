@extends("layouts.admin")
@section("title", "Reviews")
@section("content")
<div class="bg-white rounded-lg shadow overflow-x-auto">
<table class="w-full text-sm">
    <thead class="bg-stone-50 text-left"><tr><th class="p-3">Product</th><th>Customer</th><th>Rating</th><th>Review</th><th>Status</th><th></th></tr></thead>
    <tbody>
    @foreach($reviews as $review)
        <tr class="border-t">
            <td class="p-3">{{ $review->product->name }}</td>
            <td>{{ $review->user->name }}</td>
            <td>{{ $review->rating }}/5</td>
            <td class="max-w-xs truncate">{{ $review->review }}</td>
            <td>{{ ucfirst($review->status) }}</td>
            <td class="p-3 space-x-2">
                <form action="{{ route("admin.reviews.moderate", $review) }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="status" value="approved">
                    <button class="text-green-600">Approve</button>
                </form>
                <form action="{{ route("admin.reviews.moderate", $review) }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="status" value="rejected">
                    <button class="text-red-500">Reject</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
<div class="mt-4">{{ $reviews->links() }}</div>
@endsection
