<?php

namespace Ruysu\Core\Http\Requests;

use Symfony\Component\HttpFoundation\JsonResponse;

abstract class ApiFormRequest extends FormRequest
{

    /**
     * Get the proper failed validation response for the request.
     * @param  array  $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        return new JsonResponse(['errors' => $errors], 422);
    }

    /**
     * Get the response for a forbidden operation.
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function forbiddenResponse()
    {
        return new JsonResponse(['error' => 'Forbidden'], 403);
    }

}
