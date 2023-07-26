<?php

namespace App\Http\Controllers\API\V1;

use App\Models\EmailTemplates;
use Illuminate\Http\Request;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use App\Http\Resources\EmailTemplatesResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EmailTemplatesController extends BaseController
{
    /**
     * Display the specified resource.
     */
    /**
     * @OA\Post(
     *     path="/v1/email-template/details/",
     *     tags={"Email Template (Master)"},
     *     security={{"bearerAuth": {}}},
     *     summary="Get email template details",
     *     description="Get the details of a specific email template by ID",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="User id of the email template",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function show(Request $request)
    {

        try {
            #validate role
            $validatedData = Validator::make($request->all(), $this->getValiditionRuleDetails());

            #check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }
            $user_id = $request->input('user_id');
            $emailTamplate = EmailTemplates::where('user_id', $user_id)->first();

            if (!$emailTamplate) {
                return $this->sendError(__('email_template.not_found'));
            }

            return $this->sendSuccess(new EmailTemplatesResource($emailTamplate), __('email_template.detail'));
        } catch (\Exception $e) {
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError('Server Error', __('common.server_error'));
        }
    }


    /**
     * Show the form for editing the specified resource.
     */

    public function edit(EmailTemplates $emailTemplate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * @OA\Put(
     *     path="/v1/mail-template/edit/",
     *     summary="Update email template",
     *     tags={"Email Template (Master)"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         required=true,
     *         description="User Id of the email template",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="email template data",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="title",
     *                 type="string",
     *                 example="Title",
     *                 description="Title of the email template"
     *             ),
     *             @OA\Property(
     *                 property="user_id",
     *                 type="integer",
     *                 example="user_id",
     *                 description="Client Id for email template"
     *             ),
     *              @OA\Property(
     *                 property="subject",
     *                 type="string",
     *                 example="subject",
     *                 description="Subject for email"
     *             ),
     *              @OA\Property(
     *                 property="body",
     *                 type="string",
     *                 example="body",
     *                 description="Body of email tamplate"
     *             ),
     *             @OA\Property(
     *                 property="variables",
     *                 type="string",
     *                 example="Variables",
     *                 description="Variables of the email template"
     *             ),
     *             @OA\Property(
     *                 property="status",
     *                 type="enum",
     *                 example="Active/Deactive",
     *                 description="Status of the email template"
     *             ),
     *             @OA\Property(
     *                 property="puchline",
     *                 type="string",
     *                 example="puchline",
     *                 description="Puchline of the email template"
     *             ),
     *            @OA\Property(
     *                 property="footer",
     *                 type="string",
     *                 example="footer",
     *                 description="Footer of the email template"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="email template updated",
     *         @OA\MediaType(
     *             mediaType="application/json"
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */

    public function update(Request $request, EmailTemplates $emailTemplates): JsonResponse
    {
        try {
            // Validate Client SMTP Settings
            $validatedData = Validator::make($request->all(), $this->getValiditionRule());

            // Check validation
            if ($validatedData->fails()) {
                return $this->sendError(__('common.validaton_error'), $validatedData->errors());
            }

            // Find the specific email_template by user_id if provided
            $user_id = $request->input('user_id');
            if ($user_id !== '') {
                $emailTemplateData = $emailTemplates->where('user_id', $user_id)->first();

                if ($emailTemplateData) {
                    // Update the existing email_template
                    $emailTemplateData->fill($request->all());
                    $emailTemplateData->save();
                    // Return response
                    return $this->sendCreated([], __('email_template.update_success'));
                } else {
                    // Create a new email_template
                    $emailTemplateData = new EmailTemplates($request->all());
                    $emailTemplateData->save();
                    // Return response
                    return $this->sendCreated([], __('email_template.update_success'));
                }
            } else {
                // Update the clientSmtp properties
                $emailTemplates->fill($request->all());
                $emailTemplates->save();
                // Return response
                return $this->sendCreated([], __('email_template.success'));
            }
        } catch (Exception $e) {
            print_r($e->getMessage());die;
            Log::error($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine());
            return $this->sendServerError(__('common.server_error'), $e->getMessage());
        }
    }

    private function getValiditionRule()
    {
        return [
            'title' => ['required'],
            'user_id' => ['required', 'integer'],
            'subject' => ['required'],
            'body' => ['required'],
            'variables' => ['required'],
            'status' => ['required'],
            'puchline' => ['required'],
            'footer' => ['required']
        ];
    }
    private function getValiditionRuleDetails()
    {
        return [
            'user_id' => ['required', 'integer']
        ];
    }
}