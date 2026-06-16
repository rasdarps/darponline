@props([
    'products',
    'columns' => 3,
    'showColorSwatches' => true,
    'showCompare' => true,
    'showWishlist' => true,
    'showQuickView' => true,
    'showCart' => true,
    'showPagination' => true
])

<div class="row ltn__product-grid-wrapper">
    @if($products->count() > 0)
        @foreach($products as $product)
            <x-product-card 
                :product="$product"
                :columns="$columns"
                :showColorSwatches="$showColorSwatches"
                :showCompare="$showCompare"
                :showWishlist="$showWishlist"
                :showQuickView="$showQuickView"
                :showCart="$showCart"
            />
        @endforeach
        
        @if($showPagination)
            <div class="col-lg-12">
                <div class="ltn__pagination-area text-center">
                    {{ $products->links() }}
                </div>
            </div>
        @endif
    @else
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <h4>No Products Found</h4>
                <p class="text-muted">Check back later for new products</p>
            </div>
        </div>
    @endif
</div>