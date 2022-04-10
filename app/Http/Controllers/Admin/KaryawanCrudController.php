<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\KaryawanRequest;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Redirect;

/**
 * Class KaryawanCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class KaryawanCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\InlineCreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {store as traitStore;}
    use \App\Http\Controllers\Admin\Operations\DeleteDeviceOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Karyawan::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/karyawan');
        CRUD::setEntityNameStrings('karyawan', 'Karyawan');
        $this->crud->allowAccess('deletedevice');
        $this->crud->hasAccess('deletedevice');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setFromDb(); // columns

        $this->crud->modifyColumn('user_id', [
            'name'         => 'user', // name of relationship method in the model
            'type'         => 'relationship',
            'label'        => 'User Account', // Table column heading
            'attribute'    => 'email'
        ]);
        $this->crud->modifyColumn('divisi_id', [
            'name'         => 'divisi', // name of relationship method in the model
            'type'         => 'relationship',
            'label'        => 'Divisi', // Table column heading
            'attribute'    => 'nama'
        ]);
        $this->crud->modifyColumn('jam_kerja_id', [
            'name'         => 'jam_kerja', // name of relationship method in the model
            'type'         => 'relationship',
            'label'        => 'Jam Kerja', // Table column heading
            'attribute'    => 'nama'
        ]);
        $this->crud->modifyColumn('biometric_id', [
            'name'         => 'biometric_id', // name of relationship method in the model
            'type'         => 'closure',
            'label'        => 'Kunci Biometrik', // Table column heading
            'function' => function ($entry) {
                if($entry->biometric_id != null){
                    return 'Terdaftar';
                }else{
                    return 'Belum Terdaftar';
                }
            },
            'wrapper' => [
                'element' => 'span',
                'class'   => function ($crud, $column, $entry, $related_key) {
                    if ($entry->biometric_id != null) {
                        return 'badge badge-success';
                    }

                    return 'badge badge-default';
                },
            ],
        ]);
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(KaryawanRequest::class);

        CRUD::setFromDb(); // fields
        $this->crud->removeField('user_id');
        $this->crud->removeField('biometric_id');
        $this->crud->field('nik')->type('number');
        $this->crud->field('nip')->type('number');
        $this->crud->modifyField('divisi_id', [
            'type' => 'select2',
            'entity' => 'divisi', // the relationship name in your Model
            'attribute' => 'nama', // attribute on Article that is shown to admin,
            'ajax' => true,
            'inline_create' => true,
        ]);
        $this->crud->modifyField('jam_kerja_id', [
            'type' => 'select2',
            'entity' => 'jam_kerja', // the relationship name in your Model
            'attribute' => 'nama', // attribute on Article that is shown to admin,
            'ajax' => true,
            'inline_create' => true,
        ]);

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
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

    public function store(){
        if($this->crud->getRequest()){
            $this->crud->setRequest($this->crud->validateRequest()); 
            $this->crud->unsetValidation();
            \Alert::add('info', 'Karyawan & Akun pengguna berhasil dibuat! (Password default adalah : password)')->flash();
            return $this->traitStore();
        }else{
            return false;
        }
    }
}
