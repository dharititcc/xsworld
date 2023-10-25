<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="XS World Documentation",
 *      description="L5 Swagger OpenApi description",
 *      @OA\Contact(
 *          email="harshad@devsinindia.com"
 *      ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="https://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 * @OA\Server(
 *      url="http://127.0.0.1:8000",
 *      description="Demo API Server"
 * )
 * @OA\Server(
 *      url="https://express.itcc.net.au",
 *      description="Staging API Server"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
