<?php

namespace App\DataTables\Admin\Settings;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProductDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
        ->addColumn('checkbox', function($row) {
            return '<input type="checkbox" class="product-checkbox" value="' . $row->id . '">';
        })
        ->addColumn('product_info', function($row) {
            $imageUrl = $row->featured_image 
                ? asset('storage/' . $row->featured_image) 
                : asset('images/no-image.png');

                $media = $row->getMedia('featured_image');
            
                $mediaArray = $row->getMedia('featured_image');
                $count = count($mediaArray);
                if ($count > 0) {
                    for ($i = $count - 1; $i >= 0; $i--) {
                        $media = $mediaArray[$i];
                        if ($media) {
                           $imageUrl =  $media->getUrl('thumb');
                        }
                    }
                }
                $imageUrl = $row->getFirstMediaUrl('featured_image', 'thumb');
                
            return '
                <div class="d-flex align-items-center">
                    <div class="product-img mr-3">
                        <img src="' . $imageUrl . '" alt="' . e($row->name) . '" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                    </div>
                    <div>
                        <strong>' . e($row->name) . '</strong>
                        <br>
                        <small class="text-muted">SKU: ' . e($row->sku ?? 'N/A') . '</small>
                    </div>
                </div>
            ';
        })
        ->addColumn('category', function($row) {
            return $row->category ? e($row->category->name) : '<span class="text-muted">Uncategorized</span>';
        })
        ->addColumn('price', function($row) {
            if ($row->is_on_sale && $row->sale_price) {
                return '
                    <div>
                        <span class="text-muted"><del>₵' . number_format($row->price, 2) . '</del></span>
                        <br>
                        <span class="text-danger font-weight-bold">₵' . number_format($row->sale_price, 2) . '</span>
                        <br>
                        <span class="badge badge-success">-' . $row->discount_percentage . '%</span>
                    </div>
                ';
            }
            return '<span class="font-weight-bold">₵' . number_format($row->price, 2) . '</span>';
        })
        ->addColumn('stock', function($row) {
            $status = $row->quantity > 0 ? 'in_stock' : 'out_of_stock';
            $badgeClass = $status == 'in_stock' ? 'success' : ($status == 'low_stock' ? 'warning' : 'danger');
            $statusText = $status == 'in_stock' ? 'In Stock' : ($status == 'low_stock' ? 'Low Stock' : 'Out of Stock');
            
            return '
                <div>
                    <span class="badge badge-' . $badgeClass . '">' . $statusText . '</span>
                    <br>
                    <small>' . number_format($row->quantity) . ' units</small>
                </div>
            ';
        })
        ->addColumn('status', function($row) {
            $checked = $row->is_active ? 'checked' : '';
            return '
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input toggle-status" 
                           id="status-toggle-' . $row->id . '" 
                           data-id="' . $row->id . '"
                           ' . $checked . '>
                    <label class="custom-control-label" for="status-toggle-' . $row->id . '">
                        <span class="status-text">' . ($row->is_active ? 'Active' : 'Inactive') . '</span>
                    </label>
                </div>
            ';
        })
        ->addColumn('online_status', function($row) {
            $checked = $row->is_online ? 'checked' : '';
            return '
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input toggle-online" 
                           id="online-toggle-' . $row->id . '" 
                           data-id="' . $row->id . '"
                           ' . $checked . '>
                    <label class="custom-control-label" for="online-toggle-' . $row->id . '">
                        <i class="fas ' . ($row->is_online ? 'fa-globe text-success' : 'fa-eye-slash text-muted') . '"></i>
                    </label>
                </div>
            ';
        })
        ->addColumn('featured', function($row) {
            $checked = $row->is_featured ? 'checked' : '';
            return '
                <div class="text-center">
                    <input type="checkbox" class="toggle-featured" 
                           data-id="' . $row->id . '"
                           ' . $checked . '
                           data-toggle="toggle"
                           data-on="Yes"
                           data-off="No"
                           data-size="small"
                           data-onstyle="success"
                           data-offstyle="secondary">
                </div>
            ';
        })
        ->addColumn('actions', function($row) {
            $viewUrl = route('products.show', $row->id);
            $editUrl = route('products.edit', $row->id);
            $deleteUrl = route('products.destroy', $row->id);
            
            return '
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-info view-product" 
                            data-id="' . $row->id . '" 
                            data-name="' . e($row->name) . '">
                        <i class="fas fa-eye"></i>
                    </button>
                    <a href="' . $editUrl . '" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button type="button" class="btn btn-sm btn-danger delete-product" 
                            data-id="' . $row->id . '" data-name="' . e($row->name) . '">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            ';
        })
        ->addColumn('image', function ($row) {

            $media = $row->getMedia('featured_image');
            
            $mediaArray = $row->getMedia('featured_image');
            $count = count($mediaArray);
            if ($count > 0) {
                for ($i = $count - 1; $i >= 0; $i--) {
                    $media = $mediaArray[$i];
                    if ($media) {
                        return '<img src="'. $media->getUrl('thumb') .'" border="0" style="display:block;visibility:visible;" width="100" height="100">';
                    }
                }
            }
            return '<img src="'. $row->getFirstMediaUrl('featured_image', 'thumb') .'" border="0" style="display:block;visibility:visible;" width="100" height="100">';
         })    
        ->editColumn('created_at', function($row) {
            return Carbon::parse($row->created_at)->format('d M, Y');
        })
        ->filterColumn('product_info', function($query, $keyword) {
            $query->where('name', 'like', "%{$keyword}%")
                  ->orWhere('sku', 'like', "%{$keyword}%");
        })
        ->rawColumns(['checkbox', 'image',  'product_info', 'category', 'price', 'stock', 'status', 'online_status', 'featured', 'actions'])
        ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Product $model): QueryBuilder
    {
        $query = $model->newQuery()
        ->with(['category', 'brand', 'unit'])
        ->orderBy('created_at', 'desc');
    
    // Filter by category
    if ($this->category_id) {
        $query->where('shop_category_id', $this->category_id);
    }
    
    // Filter by status
    if ($this->status !== null && $this->status !== '') {
        $query->where('is_active', $this->status);
    }
    
    // Filter by online status
    if ($this->online_status !== null && $this->online_status !== '') {
        $query->where('is_online', $this->online_status);
    }
    
    // Filter by stock status
    if ($this->stock_filter) {
        if ($this->stock_filter == 'in_stock') {
            $query->where('quantity', '>', 0);
        } elseif ($this->stock_filter == 'low_stock') {
            $query->where('quantity', '>', 0)
                  ->whereColumn('quantity', '<=', 'low_stock_threshold');
        } elseif ($this->stock_filter == 'out_of_stock') {
            $query->where('quantity', 0);
        }
    }
    
    return $query;
}

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('product-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->parameters([
                        'dom'          => "<'row'l>Bfrtip",  //row counter button
                        'buttons'      => ['colvis', 'excel', 'csv', 'reset', 'reload'],
                        'language'     => [
                                                'search' => 'Search Products:',
                                                'lengthMenu' => 'Show _MENU_ products',
                                                'info' => 'Showing _START_ to _END_ of _TOTAL_ products',
                                                'emptyTable' => 'No products found',
                                                'zeroRecords' => 'No matching products found',
                                                'infoEmpty' => 'Showing 0 to 0 of 0 products',
                                                'infoFiltered' => '(filtered from _MAX_ total products)'
                                            ],
                        'drawCallback' => "function() {
                            initializeToggles();
                            initializeFeaturedToggles();
                            initializeDeleteButtons();
                            initializeBulkActions();
                            updateFilterSummary();
                        }",
                        'initComplete' => "function() {
                            addFilterSummary();
                        }",
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
             Column::make('checkbox')->title('<input type="checkbox" id="select-all">')->width(30)
                ->searchable(false)->orderable(false)->exportable(false)->printable(false),
            Column::make('product_info')->title('Product')->width(250),
            Column::make('category')->title('Category')->width(120),
            Column::make('price')->title('Price')->width(120),
            Column::make('stock')->title('Stock')->width(100),
            Column::make('status')->title('Status')->width(100),
            Column::make('online_status')->title('Online')->width(80),
            Column::make('featured')->title('Featured')->width(80),
            Column::make('created_at')->title('Added')->width(100),
            Column::make('actions')->title('Actions')->width(120)
                ->searchable(false)->orderable(false)->exportable(false)->printable(false),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Product_' . date('YmdHis');
    }
}
