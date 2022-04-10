<?php

namespace App\Http\Controllers\Admin\Operations;

use Illuminate\Support\Facades\Route;

trait DeleteDeviceOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupDeleteDeviceRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/{id}/deletedevice', [
            'as'        => $routeName.'.deletedevice',
            'uses'      => $controller.'@deletedevice',
            'operation' => 'deletedevice',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupDeleteDeviceDefaults()
    {
        $this->crud->allowAccess('deletedevice');

        $this->crud->operation('deletedevice', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation('list', function () {
            // $this->crud->addButton('top', 'deletedevice', 'view', 'crud::buttons.deletedevice');
            // $this->crud->addButton('line', 'deletedevice', 'view', 'crud::buttons.deletedevice');
            $this->crud->addButtonFromView('line', 'deletedevice', 'deletedevice', 'beginning');
        });
     
    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function deletedevice($id)
    {
        $this->crud->hasAccessOrFail('deletedevice');

        // prepare the fields you need to show
        // $this->data['crud'] = $this->crud;
        // $this->data['title'] = $this->crud->getTitle() ?? 'deletedevice '.$this->crud->entity_name;

        $this->crud->hasAccessOrFail('update');
        $this->crud->setOperation('DeleteDevice');

        $deleteDeviceEntry = $this->crud->model->find($id);
        $deleteDeviceEntry->biometric_id = null;
        $deleteDeviceEntry->save();

        return (string) $deleteDeviceEntry->push();
    }
}
