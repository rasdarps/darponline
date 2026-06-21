<?php

namespace App\DataTables\Admin\Settings;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('roles', function ($row) {
                return $row->getRoleNames()->implode(', ');
            })
            ->addColumn('edit_button', function ($row) {
                $encryptedId = Crypt::encrypt($row->id);
                $editUrl = route('users.edit', $encryptedId);
                $deleteUrl = route('users.destroy', $encryptedId);
                $csrf = csrf_field();
                $method = method_field('DELETE');

                $showUrl = route('users.show', $encryptedId);

                return '
                    <a href="'.$showUrl.'" class="btn btn-sm btn-info" title="View">
                        <i class="fas fa-eye" style="color:#fff;"></i>
                    </a>
                    <form style="display:inline-block" action="'.$editUrl.'" method="GET">
                        '.$csrf.'
                        <button style="border:none; background:green; cursor:pointer;" class="table-edit-btn btn btn-sm btn-success">
                            <i class="fas fa-edit" style="color:#fff; justify-content:center;"></i>
                        </button>
                    </form>
                    <form style="display:inline-block" action="'.$deleteUrl.'" method="POST">
                        '.$csrf.'
                        '.$method.'
                        <button type="button" style="border:none; background:red; cursor:pointer;" class="btn-delete delete-confirm-btn btn btn-sm btn-danger">
                            <i class="fas fa-trash" style="color:#fff; justify-content:center;"></i>
                        </button>
                    </form>
                ';
            })
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('jS F, Y g:i A');
            })
            ->editColumn('updated_at', function ($row) {
                return $row->updated_at
                    ? Carbon::parse($row->updated_at)->format('jS F, Y g:i A')
                    : '';
            })
            ->rawColumns(['edit_button'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery()
            ->where('user_type', '!=', 'Super Admin')
            ->orderBy('id', 'DESC');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('users-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->parameters([
                        'dom' => "<'row'l>Bfrtip",
                        'buttons' => ['colvis', 'excel', 'csv', 'reset', 'reload'],
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('name'),
            Column::make('email'),
            Column::make('roles')->searchable(false),
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
        return 'User_' . date('YmdHis');
    }
}
