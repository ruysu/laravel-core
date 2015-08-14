<?php

namespace Ruysu\Core\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class Handler extends ExceptionHandler
{

    /**
     * A list of the exception types that should not be reported.
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
    ];

    /**
     * Render an exception into an HTTP response.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        $codes = array_merge(
            range(100, 101), range(200, 206), range(400, 417), range(500, 505)
        );

        if ($e instanceof HttpException) {
            $code = $e->getStatusCode();
        } else {
            $code = $e->getCode();
        }

        if ($request->ajax()) {
            return new JsonResponse(
                ['error' => get_class($e)],
                in_array($code, $codes, true) ? $code : 500
            );
        }

        return parent::render($request, $e);
    }

}
