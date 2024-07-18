<?php
/**
 * Console API
 *
 * PHP version 7.1
 *
 * @category Modules
 *
 * @author   E-Solution <info@elgibor-solution.com>
 * @license  https://opensource.org/licenses/MIT MIT
 *
 * @link     https://elgibor-solution.com
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use FCM;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

/**
 * Base Module
 *
 * Base Module to manage CRUD
 * version 1.2
 *
 * @category Controller
 *
 * @author   E-Solution <info@elgibor-solution.com>
 * @license  https://opensource.org/licenses/MIT MIT
 *
 * @link     https://elgibor-solution.com
 */
abstract class BaseController extends Controller
{
    /**
     * Module base permission.
     *
     * @var string
     **/
    protected $permission = '';

    /**
     * Module eloquent model
     *
     * @var object
     **/
    protected $model = '';

    /**
     * Module eloquent relation function
     *
     * @var string
     **/
    protected $detailRelation = '';

    /**
     * Rows per page
     *
     * @var int
     **/
    protected $paginationRows = 10;

    /**
     * Module create validation rule
     *
     * @var array
     **/
    protected $createRule = [];

    /**
     * Module update validation rule
     *
     * @var array
     **/
    protected $updateRule = [];

    /**
     * Module index relation rule
     *
     * @var array
     **/
    protected $relationRule = [];

    /**
     * Module index validation rule
     *
     * @var array
     **/
    protected $filterRule = [];

    /**
     * Module index order rule
     *
     * @var array
     **/
    protected $orderRule = [];

    /**
     * Upload Folder under ./storage/app
     *
     * @var string
     **/
    protected $uploadDir = '';

    /**
     * Data di akses Per cabang
     *
     * @var bool
     */
    protected $filterByBranch = false;

    /**
     * Return module data
     *
     * @param  Request  $request  Request Object
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->user()->hasPermission($this->permission.'View');

        $inputs = $request->all();

        $inputs = $this->beforeSearch($inputs);

        $model = new $this->model;
        $query = $model::query();

        if (count($this->relationRule) > 0) {
            $query->with($this->relationRule);
        }

        foreach ($this->filterRule as $rule) {

            $input = isset($inputs[$rule['name']]) ? $inputs[$rule['name']] : false;
            $input = urldecode($input);

            if ($input != '' && $input != 'null' && $input != 'undefined') {
                if ($rule['operator'] == 'like') {
                    $input = "%$input%";
                }

                $query->where($rule['name'], $rule['operator'], $input);
            }
        }

        if ($this->filterByBranch) {
            if (! in_array($request->user()->roles_id, config('app.admin'))) {
                $query->where('cabang_txt', '=', $request->user()->cabang_txt);
            } elseif (! empty($cabang) && $cabang != 'null') {
                $cabang = urldecode($cabang);
                $query->where('cabang_txt', '=', $cabang);
            }
        }

        $query = $this->customSearch($request, $query);

        // Sorting Query builder
        $query->orderBy($this->orderRule['name'], $this->orderRule['operator']);

        if (isset($inputs['page']) && ! empty($inputs['page'])) {
            $list = $query->paginate($this->paginationRows);

            return $list;
        }

        $list = $query->get();

        $list = $this->afterSearch($request, $list);

        // return $list;

        return response()->json(['data' => $list], 200);
    }

    /**
     * Create a new data
     *
     * @param  Request  $request  Request Object
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->user()->hasPermission($this->permission.'Create');

        $input = $request->validate($this->createRule);

        $input = $this->beforeCreate($input);

        $invalid = $this->beforeCreateValidation($request);

        if (! empty($invalid)) {
            if (is_array($invalid)) {
                return $this->errorResponse($invalid);
            } else {
                return $this->errorResponse([], 422, $invalid);
            }
        }

        $model = new $this->model;

        $input['created_by'] = $request->user()->username;

        $result = $model::create($input);

        $this->afterCreate($request, $result);

        if (! empty($this->detailRelation)) {
            $dataDetail = $request->get('dataDetail');

            if (count($dataDetail) > 0) {
                $dataDetail = $this->beforeCreateDetail($dataDetail);

                $result->{$this->detailRelation}()->createMany($dataDetail);
            }
        }

        return response()->json(['status' => 200], 201);
    }

    /**
     * Update Module Data
     *
     * @param  Request  $request  Request Object
     * @param  int  $primaryKey  Primary ID
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $primaryKey)
    {
        $request->user()->hasPermission($this->permission.'Edit');

        $input = $request->validate($this->updateRule);

        $input = $this->beforeUpdate($input);

        $invalid = $this->beforeUpdateValidation($request);

        if (! empty($invalid)) {
            if (is_array($invalid)) {
                return $this->errorResponse($invalid);
            } else {
                return $this->errorResponse([], 422, $invalid);
            }
        }

        $model = new $this->model;
        $data = $model->findOrFail($primaryKey);

        $input['updated_by'] = $request->user()->username;

        $data->update($input);

        $this->afterUpdate($request, $data);

        if (! empty($this->detailRelation)) {
            $dataDetail = $request->get('dataDetail');

            if (count($dataDetail) > 0) {
                $dataDetail = $this->beforeUpdateDetail($dataDetail);

                $data->{$this->detailRelation}()->sync($dataDetail);
            }
        }

        return response()->json(['status' => 200], 200);
    }

    /**
     * Delete Module Data
     *
     * @param  Request  $request  Request Object
     * @param  int  $primaryKey  Primary ID
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $primaryKey)
    {
        $request->user()->hasPermission($this->permission.'Delete');

        $model = new $this->model;
        $data = $model->findOrFail($primaryKey);

        $this->beforeDelete($primaryKey);

        if (! empty($this->detailRelation)) {
            $data->{$this->detailRelation}()->delete();
        }

        $data->delete();

        $this->afterDelete($primaryKey);

        return response()->json(null, 200);
    }

    /**
     * Return Data Detail
     *
     * @return json
     */
    public function show(Request $request, $primaryKey)
    {
        $request->user()->hasPermission($this->permission.'View');

        $model = new $this->model;
        $query = $model::query();

        // Relation Query builder
        if (count($this->relationRule) > 0) {
            $query->with($this->relationRule);
        }

        $data = $query->findOrFail($primaryKey);

        $list = $data->{$this->detailRelation};

        \Log::debug($data);

        return response()->json([
            'message' => 'OK',
            'data' => $data,
        ], 200);
    }

    /**
     * Before Search Data Callback
     *
     * @param  array  $input  Params Array
     * @return array
     */
    public function beforeSearch($inputs)
    {
        return $inputs;
    }

    /**
     * After Search Data Callback
     *
     * @param  Request  $request  Request Object
     * @param  array  $input  Params Array
     * @return array
     */
    public function afterSearch($request, $inputs)
    {
        return $inputs;
    }

    /**
     * After Search Data Callback
     *
     * @param  Request  $request  Request Object
     * @param  array  $query  Eloquent Object
     * @return array
     */
    public function customSearch($request, $query)
    {
        return $query;
    }

    /**
     * Before Create Data Callback
     *
     * @param  array  $input  Params Array
     * @return array
     */
    public function beforeCreate($input)
    {
        return $input;
    }

    public function beforeCreateValidation($request)
    {
        return null;
    }

    /**
     * After Create Data Callback
     *
     * @param  Request  $request  Request Object
     * @param  array  $input  Params Array
     * @return array
     */
    public function afterCreate($request, $input)
    {

    }

    /**
     * Before Update Data Callback
     *
     * @param  array  $input  Params Array
     * @return array
     */
    public function beforeUpdate($input)
    {
        return $input;
    }

    public function beforeUpdateValidation($request)
    {
        return null;
    }

    /**
     * After Update Data Callback
     *
     * @param  Request  $request  Request Object
     * @param  array  $input  Params Array
     * @return array
     */
    public function afterUpdate($request, $input)
    {

    }

    /**
     * Before Create Data Detail Callback
     *
     * @param  array  $input  Params Array
     * @return array
     */
    public function beforeCreateDetail($input)
    {
        return $input;
    }

    /**
     * After Create Data Detail Callback
     *
     * @param  array  $input  Params Array
     * @return array
     */
    public function afterCreateDetail($input)
    {
        return $input;
    }

    /**
     * Before Update Data Detail Callback
     *
     * @param  array  $input  Params Array
     * @return array
     */
    public function beforeUpdateDetail($input)
    {
        return $input;
    }

    /**
     * After Update Data Detail Callback
     *
     * @param  array  $input  Params Array
     * @return array
     */
    public function afterUpdateDetail($input)
    {
        return $input;
    }

    public function beforeDelete($input)
    {
    }

    /**
     * After Delete Data Callback
     *
     * @param  string  $input  Params  Primary Key
     * @return void
     */
    public function afterDelete($input)
    {
    }

    /**
     * Upload CSV file
     *
     *
     * @return json
     */
    public function upload(Request $request)
    {
        $filePath = $request->file('file')->store($this->uploadDir);

        $filePath = str_replace('public/', '', $filePath);

        return response()->json(['location' => $filePath], 200);
    }

    /**
     * Send FCM Notification
     *
     * @param  string  $title
     * @param  string  $body
     * @param  string  $token
     * @return void
     */
    protected function sendNotification($title, $body, $token)
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($body)
            ->setSound('default');

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();

        $downstreamResponse = FCM::sendTo($token, $option, $notification);

    }
}