<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PresensiRequest;
use App\Models\Karyawan;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PresensiCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PresensiCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {   
        
        if(!backpack_user()->hasRole('Developer')){
            $this->crud->denyAccess('create');
            $this->crud->denyAccess('update');
            $this->crud->denyAccess('delete');
        }
        CRUD::setModel(\App\Models\Presensi::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/presensi');
        CRUD::setEntityNameStrings('presensi', 'Presensi');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->enableExportButtons();
        

        //Filter By Karyawan
        $this->crud->addFilter([
            'name' => 'select2',
            'type' => 'select2',
            'label' => 'Karyawan'
        ], function(){
            return User::NotDeveloperUser()->lazyById()->pluck('name', 'id')->toArray();
        }, function($value){
            $this->crud->addClause('where', 'user_id', $value);
        });

        //Filter By Date Range
        $this->crud->addFilter([
            'type' => 'date_range',
            'name' => 'date_range',
            'label' => 'Rentang Tanggal'
        ],false, function($value){
            $dates = json_decode($value);
            $this->crud->addClause('where', 'tanggal', '>=', $dates->from);
            $this->crud->addClause('where', 'tanggal', '<=', $dates->to);
        });

        if(!backpack_user()->hasRole('Developer') && !backpack_user()->hasRole('Admin')){
            $this->crud->addClause('where', 'user_id', backpack_user()->id);
        }
        // $this->crud->addClause('whereHas', 'roles', function($query) {
        //     $query->where('name', '!=', 'Developer');
        // });

        CRUD::setFromDb(); // columns

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
        CRUD::modifyColumn('user_id',[
            'name' => 'user_id',
            'type' => 'relationship',
            'label' => 'Nama Pengguna',
            'attribute' => 'name',
            
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('user', function ($q) use ($column, $searchTerm) {
                    $q->where('name', 'like', '%'.$searchTerm.'%');
                });
            }
        ]);

        CRUD::addColumn([
            'label' => 'Waktu Absen RealTime',
            'name' => 'created_at',
            'type' => 'datetime',
        ])->beforeColumn('keterangan');
        $this->crud->modifyColumn('tipe', [
            'name'         => 'tipe', // name of relationship method in the model
            'type'         => 'text',
            'label'        => 'Tipe Absen', // Table column heading
            'wrapper' => [
                'element' => 'span',
                'class' => function ($crud, $column, $entry, $related_key) {
                    if ($column['text'] == 'ClockIn') {
                        return 'badge badge-info';
                    }
                    return 'badge badge-warning';
                },
            ],
        ]);
        // CRUD::removeColumn('tanggal');
        
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(PresensiRequest::class);
        CRUD::setFromDb(); // fields

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
        CRUD::modifyField('user_id',[
            'label' => 'Akun Pengguna',
            'placeholder' => 'Pilih Pengguna',
            'type' => 'relationship',
            'name' => 'user_id',
            'entity' => 'user',
            'attribute' => 'email',
            'ajax' => true
        ]);
        CRUD::modifyField('tipe',[
            'label' => 'Tipe Absen',
            'placeholder' => 'Pilih Tipe Absen',
            'name' => 'tipe',
            'type' => 'select_from_array',
            'options' => [
                'ClockIn' => 'Clockin/Masuk',
                'ClockOut' => 'ClockOut/Pulang'
            ]
        ]);
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function fetchUser()
    {
        // return $this->fetch(\App\Models\User::class);
        return $this->fetch([
            'model' => User::class,
            'searchable_attributes' => ['email'],
            'paginate' => 10,
            'query' => function($query){
                return $query->where('id', '!=', 1);
            }
        ]);
    }
}
