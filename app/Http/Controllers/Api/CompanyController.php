<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Companies;
use App\Models\Job;
use App\Validators\CompanyValidator;
use App\Exceptions\InputValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\CompanyCollection;
use Exception;
use DB;

class CompanyController extends Controller
{
    static $validator = CompanyValidator::class;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $companies = Companies::with(['jobs' => function ($query) {
                $query->where('is_active', '>', 0);
            }])->get();

            $collection = new CompanyCollection($companies);

            return response()->json($collection);

        } catch (Exception $e) {}

        return response()->json(['message' => 'There was an error in query'], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $item = new CompanyResource(Companies::findOrFail($id));

            return response()->json($item);
        } catch (Exception $e) {}

        return response()->json(['message' => 'Data cannot be found'], 500);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $requestData = $request->all();
            $validator = new static::$validator;
            $useRule = $validator::$create_rules;
            $isValid = $validator->validate($requestData, $useRule);

            if (!$isValid) {
                throw new InputValidationException($validator->errors());
            }

            $company = new Companies;
            $company->name = $requestData['name'];
            $company->address = $requestData['address'];
            $company->is_active = $requestData['is_active'];
            $company->save();

            return response()->json($company);

        } catch (InputValidationException $e) {
            return response()->json([
                'message' => 'Validation input error',
                'data' => json_decode($e->getMessage())
            ], 400);
        } catch (Exception $e) {}

        return response()->json(['message' => 'Error while saving the data'], 500);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $requestData = $request->all();
            $validator = new static::$validator;
            $useRule = $validator::$update_rules;
            $isValid = $validator->validate($requestData, $useRule);

            if (!$isValid) {
                throw new InputValidationException($validator->errors());
            }

            $company = Companies::findOrFail($id);
            $company->name = $requestData['name'];
            $company->address = $requestData['address'];
            $company->is_active = $requestData['is_active'];
            $company->save();

            if ($company->is_active < 1) {
                Job::where('company_id', $company->id)->update(['is_active' => 0]);
            }

            DB::commit();
            return response()->json($company);

        } catch (InputValidationException $e) {
            return response()->json([
                'message' => 'Validation input error',
                'data' => json_decode($e->getMessage())
            ], 400);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Company could not be found'
            ], 400);
        } catch (Exception $e) {}

        DB::rollBack();
        return response()->json(['message' => 'Error while updating the data'], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $item = Companies::findOrFail($id);
            $item->delete();

            Job::where('company_id', $id)->delete();
            DB::commit();

            return response()->json(['status' => 'success'], 200);

        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Company could not be found'
            ], 400);
        } catch (Exception $e) {}
        DB::rollBack();
        return response()->json(['message' => 'Error while deleting the data'], 500);
    }
}
