<?php

namespace App\DataTables\Admin\Settings;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CategoryDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'category.action')
            ->addColumn('edit_button', function($row) {
                    $csrf = csrf_field();
                    $method = method_field('DELETE');

                    return '
                        <form style="display:inline-block" action="'.route('categories.edit', Crypt::encrypt($row->id)).'" method="GET">
                            '.$csrf.'
                            <button style="border:none; background:none; cursor:pointer;" class="table-edit-btn btn btn-sm btn-success">
                                <i class="fas fa-edit" style="color:#010033; justify-content:center;"></i>
                            </button>
                        </form>
                        <form style="display:inline-block" action="'.route('categories.destroy', Crypt::encrypt($row->id)).'" method="POST">
                            '.$csrf.'
                            '.$method.'
                            <button type="button" style="border:none; background:none; cursor:pointer;" class="btn-delete btn btn-sm btn-danger">
                                <i class="fas fa-trash" style="color:red; justify-content:center;"></i>
                            </button>
                        </form>
                    ';
                })
            
            ->editColumn('created_at', function($row)
            {
                return  Carbon::parse($row->created_at)->format('jS F, Y g:i A');
            })
            ->addColumn('show_description', function($row) {
                $description = $row->description ?? 'No description available';
                $shortDesc = strlen($description) > 50 ? substr($description, 0, 50) . '...' : $description;
                
                return '
                    <div class="description-wrapper">
                        <span class="description-preview">' . e($shortDesc) . '</span>
                        <button type="button" class="btn btn-sm btn-info view-description-btn" 
                                data-id="' . $row->id . '"
                                data-name="' . e($row->name) . '"
                                data-description="' . e($description) . '">
                            <i class="fas fa-eye"></i> View
                        </button>
                    </div>
                ';
            })
            ->addColumn('show_on_website', function($row) {
                $isChecked = $row->show_on_homepage? 'checked' : '';
                return '
                    <div class="custom-control custom-switch">
                        <input type="checkbox" 
                               class="custom-control-input toggle-website-status" 
                               id="website-toggle-' . $row->id . '" 
                               data-id="' . $row->id . '"
                               ' . $isChecked . '>
                        <label class="custom-control-label" for="website-toggle-' . $row->id . '">
                            <span class="status-text">' . ($row->show_on_homepage ? 'Visible' : 'Hidden') . '</span>
                        </label>
                    </div>
                ';

            })
            ->editColumn('updated_at', function($row) {
                return $row->updated_at
                    ? Carbon::parse($row->updated_at)->format('jS F, Y g:i A')
                    : '';
            })

             ->rawColumns(['edit_button', 'show_description', 'show_on_website'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Category $model): QueryBuilder
    {        

        return $model->newQuery()->orderBy('name', 'asc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('category-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                     ->orderBy(1)
                    ->selectStyleSingle()
                    ->parameters([
                        'dom' => "<'row'l>Bfrtip",
                        'buttons' => ['excel', 'csv', 'reset', 'reload'],
                        'pageLength' => 10,
                        'responsive' => true,
                        'language' => [
                            'search' => 'Search Categories:',
                            'lengthMenu' => 'Show _MENU_ entries',
                            'info' => 'Showing _START_ to _END_ of _TOTAL_ categories',
                        ],
                        'drawCallback' => "function() {
                            initializeToggles();
                            initializeDescriptionModals();
                        }",
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('name'),
            Column::make('description')->title('Description')->width('300px')->visible(false),
            Column::make('show_description')->title('Description')->width('250px')->searchable(false),
            Column::make('show_on_website')->title('Website Visibility')->width('150px')->searchable(false),

            Column::make('created_at'),
            Column::make('updated_at'),
            Column::make('edit_button')->title('Actions')
            ->searchable(false)->printable(false)->exportable(false),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Category_' . date('YmdHis');
    }
}
