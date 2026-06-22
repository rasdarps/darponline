<div class="product-details">
    <div class="row">
        <div class="col-md-12">
            <h4>{{ $product->name }}</h4>
            <p class="text-muted">
                <strong>Barcode:</strong> {{ $product->barcode ?? 'N/A' }}
            </p>
            
            <hr>
            
            <div class="product-info">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td width="40%"><strong>Category:</strong></td>
                        <td>{{ $product->category->name ?? 'Uncategorized' }}</td>
                    </tr>
                    @if($product->unit)
                    <tr>
                        <td><strong>Unit:</strong></td>
                        <td>{{ $product->unit->name ?? 'N/A' }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td><strong>Price:</strong></td>
                        <td>
                            @if($product->is_on_sale && $product->sale_price)
                                <span class="text-muted"><del>₵{{ number_format($product->price, 2) }}</del></span>
                                <span class="text-danger font-weight-bold ml-2">₵{{ number_format($product->sale_price, 2) }}</span>
                            @else
                                <span class="font-weight-bold">₵{{ number_format($product->price, 2) }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Stock Quantity:</strong></td>
                        <td>
                            @if($product->stock_quantity <= 0)
                                <span class="badge badge-danger">Out of Stock</span>
                            @elseif($product->stock_quantity <= ($product->low_stock_threshold ?? 5))
                                <span class="badge badge-warning">Low Stock ({{ $product->stock_quantity }})</span>
                            @else
                                <span class="badge badge-success">{{ $product->stock_quantity }} units</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            
            @if($product->tags)
            <hr>
            <div class="product-tags">
                <strong>Tags:</strong>
                <div class="mt-1">
                    @foreach(explode(',', $product->tags) as $tag)
                        <span class="badge badge-secondary">{{ trim($tag) }}</span>
                    @endforeach
                </div>
            </div>
            @endif
            
            <hr>
            <div class="text-muted small">
                <div><i class="fas fa-calendar-plus"></i> Created: {{ $product->created_at->format('d M, Y h:i A') }}</div>
                <div><i class="fas fa-calendar-edit"></i> Last Updated: {{ $product->updated_at->format('d M, Y h:i A') }}</div>
            </div>
        </div>
    </div>
</div>

<style>
    .product-details {
        padding: 10px;
    }
    .product-info table {
        margin-bottom: 0;
    }
    .product-info td {
        padding: 5px 0;
    }
    .product-description {
        max-height: 200px;
        overflow-y: auto;
    }
</style>

<script>
    // Optional: Add to cart from modal
    function addToCart(productId) {
        // Your add to cart logic here
        console.log('Add to cart:', productId);
        Notiflix.Notify.info('Add to cart feature coming soon');
    }
</script>