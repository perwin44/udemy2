<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
//use Yajra\DataTables\Services\DataTable;

 class UsersDataTable 
{
    /**
     * Build the DataTable class.
     *extends DataTable 
     * @param QueryBuilder $query Results from query() method.
     */
    // public function dataTable(QueryBuilder $query): EloquentDataTable
    // {
    //     return (new EloquentDataTable($query))
    //         ->addColumn('action', 'users.action')
    //         ->setRowId('id');
    // }

    /**
     * Get the query source of dataTable.
     */
    // public function query(User $model): QueryBuilder
    // {
    //     return $model->newQuery();
    // }

    /**
     * Optional method if you want to use the html builder.
     */
    // public function html(): HtmlBuilder
    // {
    //     return $this->builder()
    //                 ->setTableId('users-table')
    //                 ->columns($this->getColumns())
    //                 ->minifiedAjax()
                    //->dom('Bfrtip')
    //                 ->orderBy(1)
    //                 ->selectStyleSingle()
    //                 ->buttons([
    //                     Button::make('excel'),
    //                     Button::make('csv'),
    //                     Button::make('pdf'),
    //                     Button::make('print'),
    //                     Button::make('reset'),
    //                     Button::make('reload')
    //                 ]);
    // }

    /**
     * Get the dataTable columns definition.
     */
    // public function getColumns(): array
    // {
    //     return [
    //         Column::computed('action')
    //               ->exportable(false)
    //               ->printable(false)
    //               ->width(60)
    //               ->addClass('text-center'),
    //         Column::make('id'),
    //         Column::make('name'),
    //         Column::make('email'),
    //         Column::make('created_at'),
    //         Column::make('updated_at'),
    //         Column::make('email_verified_at'),
    //     ];
    // }

    /**
     * Get the filename for export.
     */
    // protected function filename(): string
    // {
    //     return 'Users_' . date('YmdHis');
    // }
}
