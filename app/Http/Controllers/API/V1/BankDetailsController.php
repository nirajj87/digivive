<?php

namespace App\Http\Controllers\API\V1;

use App\Models\BankDetails;
use Illuminate\Http\Request;
use App\Http\Resources\BankDetailsResource;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\V1\BaseController as BaseController;

class BankDetailsController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    
    /**
     * Store a newly created resource in storage.
     *   
     * @OA\Post(
     *   path="/v1/bank-details",
     *   summary="Create or Update Client Bank Details",
     *   tags={"Client Bank Details (Master)"},
     *   security={{"bearerAuth": {}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *         required={"beneficiary_name", "bank_name", "account_number", "ifsc_code", "bank_address", "cancelled_cheque", "gstin","cin","pan","user_id", "status"},
     *         @OA\Property(
     *           property="beneficiary_name",
     *           type="string"
     *         ),
     *         @OA\Property(
     *           property="bank_name",
     *           type="string"
     *         ),
     *         @OA\Property(
     *           property="account_number",
     *           type="integer"
     *         ),
     *         @OA\Property(
     *           property="ifsc_code",
     *           type="string"
     *         ),
     *         @OA\Property(
     *           property="swift_code",
     *           type="string"
     *         ),
     *         @OA\Property(
     *           property="bank_address",
     *           type="string"
     *         ),
     *         @OA\Property(
     *           property="cancelled_cheque",
     *           type="file"
     *         ),
     *         @OA\Property(
     *           property="gstin",
     *           type="string"
     *         ),
     *          @OA\Property(
     *           property="cin",
     *           type="string"
     *         ),
     *          @OA\Property(
     *           property="pan",
     *           type="string"
     *         ), 
     *          @OA\Property(
     *           property="payment_gateway_id",
     *           type="integer"
     *         ),
     *         @OA\Property(
     *           property="status",
     *           type="string"
     *         ),
     *         @OA\Property(
     *           property="user_id",
     *           type="integer"
     *         ),
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="Client Bank Details created successfully",
     *     @OA\JsonContent(
     *       type="object",
     *       
     *       @OA\Property(property="message", type="string")
     *     )
     *   ),
     *   @OA\Response(response=400, description="Validation Error"),
     *   @OA\Response(response=401, description="Unauthenticated"),
     *   @OA\Response(response=500, description="Server Error")
     * )
     */
    public function store(Request $request)
    {
        try {
            
            #validate role
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());
            
            #check validation
            if ($validatedData->fails()){
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());  
            }
            if ($request->hasFile('cancelled_cheque')) {
                $file = $request->file('cancelled_cheque');
                $filename = $file->getClientOriginalName();
                $user_id = $request->input('user_id');
                $folder = "user/$user_id/bank_details";
                $path = $file->storeAs($folder, $filename, 'public');
            }
            #Bank details data
            $data = [
                'beneficiary_name'              => $request->input('beneficiary_name'),
                'bank_name'                     => $request->input('bank_name'),
                'account_number'                => $request->input('account_number'),
                'ifsc_code'                     => $request->input('ifsc_code'),
                'swift_code'                    => $request->input('swift_code'),
                'bank_address'                  => json_encode($request->input('bank_address')),
                'cancelled_cheque'              => $path ?? $request->input('cancelled_cheque'),
                'payment_gateway_id'            => $request->input('payment_gateway_id'),
                'gstin'                         => $request->input('gstin'),
                'cin'                           => $request->input('cin'),
                'pan'                           => $request->input('pan'),
                'status'                        => $request->input('status'),
            ];

            #create settings
            $settings = BankDetails::updateOrCreate(['user_id' => $request->input('user_id')],$data);

            #return response
            return $this->sendCreated([], __('bank_details.successful'));

        } catch (\Exception $e) {
            // print_r($e->getMessage()); die;
            Log::error($e->getMessage().' File:'.$e->getFile().' Line:'.$e->getLine());
            return $this->sendServerErrorResponse('Server Error',__('common.server_error')); 
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    
    /**
     * Display the specified resource.
     *
     * @OA\Get(
     *   path="/v1/bank-details/get-user-bank-details/{user_id}",
     *   summary="Get User Bank Details",
     *   tags={"Client Bank Details (Master)"},
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *     name="user_id",
     *     in="path",
     *     required=true,
     *     description="ID of the user",
     *     @OA\Schema(
     *       type="integer",
     *       format="int64"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Bank Details retrieved successfully",
     *     @OA\JsonContent(
     *       type="object",
     *  
     *       @OA\Property(property="message", type="string")
     *     )
     *   ),
     *   @OA\Response(response=401, description="Unauthenticated"),
     *   @OA\Response(response=500, description="Server Error")
     * )
     */
    public function getUserBankDetails($user_id)
    {
        try{
            $details = BankDetails::where('user_id', $user_id)->first();
            return $this->sendSuccess(new BankDetailsResource($details), __('bank_details.detail'));
        } catch(\Exception $e){
            Log::error($e->getMessage().' File:'.$e->getFile().' Line:'.$e->getLine());
            return $this->sendServerError('Server Error',__('common.server_error')); 
        }
    }

    private function getValiditionRule(){
        return [
            'beneficiary_name'          => ['required'],
            'bank_name'                 => ['required'],
            'account_number'            => ['integer','required'],
            'ifsc_code'                 => ['required'],
            'bank_address'              => ['required'],
            'cancelled_cheque'          => ['required'],
            'gstin'                     => ['required'],
            'cin'                       => ['required'],
            'pan'                       => ['required'],
            'status'                    => ['required'],
            'user_id'                   => ['integer','required'],
        ];
    }
}
