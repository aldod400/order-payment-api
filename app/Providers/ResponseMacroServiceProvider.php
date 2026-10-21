<?php

namespace App\Providers;

use App\Http\Resources\BaseResource;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro('api', function (
            int $statusCode = 200,
            string $message = 'Success',
            mixed $data = null,
            mixed $errors = null
        ) {
            $response = [
                'status' => $statusCode,
                'message' => $message,
            ];

            if ($errors !== null) {
                $response['error'] = $errors;

                return response()->json($response, $statusCode, [], JSON_UNESCAPED_UNICODE);
            }

            if ($data !== null) {
                if ($data instanceof BaseResource) {
                    $resourceData = $data->toArray(request());

                    if (isset($resourceData['data'], $resourceData['current_page'])) {
                        $response = array_merge($response, $resourceData);
                    } else {
                        $response['data'] = $resourceData;
                    }
                } else {
                    $response['data'] = $data;
                }
            }

            return response()->json($response, $statusCode, [], JSON_UNESCAPED_UNICODE);
        });
    }
}
