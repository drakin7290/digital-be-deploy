<?php

namespace App\Http\Controllers;

class APIController extends Controller
{
    /**
     *  @SWG\Swagger(  
     *      basePath="/api/v1",
     *      schemes={"http","https"},
     *      @SWG\SecurityScheme(
     *          securityDefinition="Bearer",
     *          type="apiKey",
     *          name="Authorization",
     *          in="header"
     *      ),
     *      @SWG\Info(
     *          title="REST API Docs",
     *          description="REST API Docs.",
     *          version="1.0.0",
     *      )
     *  )
     *  @SWG\Post(
     *     path="/customer/login",
     *     tags={"Authentication"},
     *     summary="Get a JWT via given credentials",
     *     description="Returns a JWT token",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="student_id",
     *         in="formData",
     *         description="Student ID",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="password",
     *         in="formData",
     *         description="Password",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation",
     *         @SWG\Header(
     *             header="Authorization",
     *             description="Bearer {token}",
     *             type="string"
     *         ),
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(
     *                 property="error",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @SWG\Property(
     *                 property="data",
     *                 type="object",
     *                 @SWG\Property(
     *                     property="access_token",
     *                     type="string",
     *                     example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6Ly9sb2NhbGhvc3QvYXBpL2xvZ2luIiwiaWF0IjoxNjIwNjIxNjA4LCJleHAiOjE2MjA2Mjc2MDgsIm5iZiI6MTYyMDYyMTYwOCwianRpIjoiTzRVMTM4Q2Fpd1JjM2lxbSJ9.SD_iJmpMSjcnhldl-SP_Gb0Lv9pTde0J32A-JfKzZoU"
     *                 ),
     *                 @SWG\Property(
     *                     property="token_type",
     *                     type="string",
     *                     example="bearer"
     *                 ),
     *                 @SWG\Property(
     *                     property="expires_in",
     *                     type="integer",
     *                     example=3600
     *                 )
     *             )
     *         )
     *     ),
     *      @SWG\Response(
     *         response=400,
     *         description="Invalid request data",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(
     *                 property="error",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @SWG\Property(
     *                 property="message",
     *                 type="object",
     *                 example={"email": {"The email field is required."}}
     *             ),
     *             @SWG\Property(
     *                 property="type",
     *                 type="integer",
     *                 example=400
     *             )
     *         )
     *     ),
     *     @SWG\Response(
     *         response=401,
     *         description="Unauthorized"        
     *      )
     *  ),
     * 
     * 
     *  @SWG\Post(
     *     path="/customer/attendance",
     *     tags={"Checkin"},
     *     security = { { "bearerAuth": {} } },
     *     summary="Checkin user",
     *     description="Returns a status checkin",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation",
     *         @SWG\Header(
     *             header="Authorization",
     *             description="Bearer {token}",
     *             type="string"
     *         ),
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(
     *                 property="error",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @SWG\Property(
     *                 property="data",
     *                 type="string",
     *                 example="Successful attendance"
     *             )
     *         )
     *     ),
     *      @SWG\Response(
     *         response=400,
     *         description="Invalid request data",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(
     *                     property="error",
     *                     type="boolean",
     *                     example="true"
     *                 ),
     *                 @SWG\Property(
     *                     property="data",
     *                     type="string",
     *                     example="This user does not exist"
     *                 ),
     *         )
     *     ),
     *     @SWG\Response(
     *         response=401,
     *         description="Unauthorized"        
     *      )
     *  ),
     * 
     *  @SWG\Get(
     *     path="/customer/list_attendance",
     *     tags={"Checkin"},
     *     security = { { "bearerAuth": {} } },
     *     summary="Get list date checkin of user",
     *     description="Returns a list dates",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation",
     *         @SWG\Header(
     *             header="Authorization",
     *             description="Bearer {token}",
     *             type="string"
     *         ),
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(
     *                 property="error",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @SWG\Property(
     *                 property="data",
     *                 type="object",
     *                 example="['2023-06-05, 2023-06-04, 2023-06-03']"
     *             )
     *         )
     *     ),
     *     @SWG\Response(
     *         response=401,
     *         description="Unauthorized"        
     *      )
     *  ),
     *
     *  @SWG\Get(
     *     path="/customer/streak",
     *     tags={"Checkin"},
     *     security = { { "bearerAuth": {} } },
     *     summary="Get list streak checkin",
     *     description="Returns a streak",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation",
     *         @SWG\Header(
     *             header="Authorization",
     *             description="Bearer {token}",
     *             type="string"
     *         ),
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(
     *                 property="error",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @SWG\Property(
     *                 property="streak",
     *                 type="number",
     *                 example="2"
     *             )
     *         )
     *     ),
     *     @SWG\Response(
     *         response=401,
     *         description="Unauthorized"        
     *      )
     *  ),
     * @SWG\Definition(
     *      definition="Customer",
     *      type="object",
     *      required={"name", "email", "student_id"},
     *      @SWG\Property(
     *          property="name",
     *          type="string",
     *          description="Name of the customer"
     *      ),
     *      @SWG\Property(
     *          property="student_id",
     *          type="string",
     *          description="Student id of the customer"
     *      ),
     *      @SWG\Property(
     *          property="avatar",
     *          type="string",
     *          description="Avatar of the customer"
     *      ),
     *      @SWG\Property(
     *          property="email",
     *          type="string",
     *          description="Email of the customer"
     *      ),
     *      @SWG\Property(
     *          property="phone",
     *          type="string",
     *          description="Phone number of the customer"
     *      )
     * )
     */
     }