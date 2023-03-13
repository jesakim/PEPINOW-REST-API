<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="3.1.0",
 *      title="Pepinow",
 *      description="Pepinow Api Documentation",)
 *  @OA\SecurityScheme(
 *      securityScheme="bearer_token",
 *      type="http",
 *      scheme="bearer"
 * )
 *
 */

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
