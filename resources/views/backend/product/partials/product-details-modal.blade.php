<div class="product-details">
    <div class="row">
        <div class="col-md-5">
            <div class="product-image text-center">
                @if($product->hasMedia('featured_image'))
                    {{$product->getFirstMedia('featured_image')->img('thumb')}}
                @else
                    <div class="no-image bg-light p-5 text-center rounded">
                        <i class="fas fa-image fa-4x text-muted"></i>
                        <p class="text-muted mt-2">No image available</p>
                    </div>
                @endif
            </div>
            
            @if($product->hasMedia('gallery_images'))
            <div class="gallery-images mt-3">
                <h6>Gallery Images:</h6>
                <div class="row">
                    @foreach($product->getMedia('gallery_images') as $image)
                    <div class="col-6 mb-2">

                        {!! $image->img('thumb')->attributes([
                            'class' => 'img-fluid rounded',
                            'style' => 'width: 150px; height: 150px; object-fit: cover; cursor: pointer;',
                            'onclick' => "window.open(this.src,'_blank')"
                        ]) !!}
                        {{-- <img src="{{ asset('storage/' . $image->image) }}" 
                             class="img-fluid rounded" 
                             style="height: 60px; width: 60px; object-fit: cover; cursor: pointer;"
                             onclick="window.open(this.src, '_blank')"> --}}
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        
        <div class="col-md-7">
            <h4>{{ $product->name }}</h4>
            <p class="text-muted">
                <strong>SKU:</strong> {{ $product->sku ?? 'N/A' }}
                @if($product->barcode)
                <br><strong>Barcode:</strong> {{ $product->barcode }}
                @endif
            </p>
            
            <hr>
            
            <div class="product-info">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td width="40%"><strong>Category:</strong></td>
                        <td>{{ $product->category->name ?? 'Uncategorized' }}</td>
                    </tr>
                    @if($product->brand)
                    <tr>
                        <td><strong>Brand:</strong></td>
                        <td>{{ $product->brand->name ?? 'N/A' }}</td>
                    </tr>
                    @endif
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
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            @if($product->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-secondary">Inactive</span>
                            @endif
                            
                            @if($product->is_online)
                                <span class="badge badge-info ml-1">Online</span>
                            @endif
                            
                            @if($product->is_featured)
                                <span class="badge badge-warning ml-1">Featured</span>
                            @endif
                            
                            @if($product->is_new)
                                <span class="badge badge-primary ml-1">New Arrival</span>
                            @endif
                        </td>
                    </tr>
                    @if($product->weight || $product->length || $product->width || $product->height)
                    <tr>
                        <td><strong>Dimensions:</strong></td>
                        <td>
                            @if($product->weight) Weight: {{ $product->weight }} kg<br>@endif
                            @if($product->length || $product->width || $product->height)
                                Size: {{ $product->length }} x {{ $product->width }} x {{ $product->height }} cm
                            @endif
                        </td>
                    </tr>
                    @endif
                    @if($product->min_order_quantity > 1)
                    <tr>
                        <td><strong>Min Order Qty:</strong></td>
                        <td>{{ $product->min_order_quantity }}</td>
                    </tr>
                    @endif
                </table>
            </div>
            
            @if($product->short_description)
            <hr>
            <div class="product-short-description">
                <strong>Short Description:</strong>
                <p class="mt-1">{{ $product->short_description }}</p>
            </div>
            @endif
            
            @if($product->description)
            <hr>
            <div class="product-description">
                <strong>Full Description:</strong>
                <div class="mt-2">{!! nl2br(e($product->description)) !!}</div>
            </div>
            @endif
            
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
    .product-image img {
        width: 100%;
        object-fit: cover;
        border-radius: 8px;
    }
    .no-image {
        background: #f8f9fa;
        border-radius: 8px;
    }
    .product-info table {
        margin-bottom: 0;
    }
    .product-info td {
        padding: 5px 0;
    }
    .gallery-images img {
        transition: transform 0.3s;
    }
    .gallery-images img:hover {
        transform: scale(1.1);
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