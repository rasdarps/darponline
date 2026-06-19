<div class="modal fade productModal  ltn__sp-modal" id="quickViewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content quick_view_content">
            <div class="modal-body ">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="qwick-view-left">
                            <div class="quick-view-learg-img">
                                <div class="quick-view-tab-content tab-content">
                                    <div class="product-main-image__item">
                                        <div class="img_box_1">
                                            <!-- Image will be loaded here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="qwick-view-right modal-product-info">
                            <div class="qwick-view-content">
                                <div class="product-rating spr-badge-caption-none">
                                    <div class="quick-view-rating rating" id="quickViewRating"></div>
                                </div>
                                <h1 class="product_title" id="quickViewTitle">Product Name</h1>
                                <div class="product-price product-info__price price-part">
                                    <span class="main" id="quickViewPrice"></span>
                                    <span class="price-box__new" id="quickViewSalePrice"></span>
                                </div>
                                <div class="short-description product-des" id="quickViewDescription"></div>
                                
                                <form id="add-item-qv" action="#" method="post">
                                    <div class="quick-view-select variants select-option-part" id="quickViewVariants"></div>
                                    
                                    <!-- Quantity with + and - buttons -->
                                    <div class="quickview-plus-minus">
                                        <div class="cart-plus-minus quantity">
                                            <div class="cart-plus-minus-box-wrapper">
                                                <button type="button" class="qtybutton dec" onclick="decrementQuantity()">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input type="text" 
                                                       min="1" 
                                                       step="1" 
                                                       value="1" 
                                                       name="quantity" 
                                                       class="cart-plus-minus-box" 
                                                       id="quickViewQuantity" 
                                                       max="999">
                                                <button type="button" class="qtybutton inc" onclick="incrementQuantity()">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="quickview-btn-cart">
                                            <button type="button" class="addtocartqv theme-default-button---- theme-btn-1 btn btn-effect-1" onclick="addToCartFromQuickView()">
                                                <i class="fas fa-shopping-cart"></i> Add to cart
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                
                                <!-- Additional product info -->
                                <div class="product-meta mt-3" id="quickViewMeta">
                                    <div class="product-sku"><strong>SKU:</strong> <span id="quickViewSku"></span></div>
                                    <div class="product-category"><strong>Category:</strong> <span id="quickViewCategory"></span></div>
                                    <div class="product-brand"><strong>Brand:</strong> <span id="quickViewBrand"></span></div>
                                    <div class="product-stock"><strong>Availability:</strong> <span id="quickViewStock"></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="close-icon" aria-hidden="true"><i class="icon-cancel for-search-close"></i></span>
                </button>
            </div>
        </div>
    </div>
</div>