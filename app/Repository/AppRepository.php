<?php 
namespace App\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AppRepository
{
    /**
     * Eloquent model instance.
     */
    protected $model;
    /**
     * load default class dependencies.
     * 
     * @param Model $model Illuminate\Database\Eloquent\Model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }
    /**
     * get all the items collection from database table using model.
     * 
     * @return Collection of items.
     */
    public function get()
    {
        return $this->model->get();
    }
    /**
     * get collection of items in paginate format.
     * 
     * @return Collection of items.
     */
    public function paginate(Request $request)
    {
        return $this->model->paginate($request->input('limit', 10));
    }
    /**
     * create new record in database.
     * 
     * @param Request $request Illuminate\Http\Request
     * @return saved model object with data.
     */
    public function store($data)
    {
        // $data = $this->setDataPayload($request);
        $item = $this->model;
        $item->fill($data);
        $item->save();
         return $item;
    }
    /**
     * update existing item.
     * 
     * @param  Integer $id integer item primary key.
     * @param Request $request Illuminate\Http\Request
     * @return send updated item object.
     */
    public function update($id, $data)
    {
        $item = $this->model->findOrFail($id);
        $item->fill($data);
        $item->save();
        return $item;
    }
    /**
     * get requested item and send back.
     * 
     * @param  Integer $id: integer primary key value.
     * @return send requested item data.
     */
    public function show($id)
    {
        return $this->model->findOrFail($id);
    }
    /**
     * Delete item by primary key id.
     * 
     * @param  Integer $id integer of primary key id.
     * @return boolean
     */
    public function delete($id)
    {
        return $this->model->destroy($id);
    }
    /**
     * set data for saving
     * 
     * @param  Request $request Illuminate\Http\Request
     * @return array of data.
     */
    protected function save(Request $request)
    {
        dd($request);
        return $request->all();
    }
}