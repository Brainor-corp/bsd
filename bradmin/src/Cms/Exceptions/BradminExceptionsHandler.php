<?php

namespace Bradmin\Cms\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

use Bradmin\Cms\Helpers\CMSHelper;
use Bradmin\Cms\Controllers\CmsController;

class BradminExceptionsHandler extends ExceptionHandler
{
        /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if($exception instanceof NotFoundHttpException)
        {
            $postExistenceCheck = CMSHelper::getByUrl($request->getPathInfo());
            if($postExistenceCheck){
                    switch ($postExistenceCheck->type) {
                        case 'page':
                            $method = 'showPage';
                            break;
                        case 'post':
                            $method = 'showPost';
                            break;
                    }
            }
            if(isset($method)){
                $controllerData = CmsController::{$method}($postExistenceCheck->slug);
                return response()->view($controllerData['view'], $controllerData['data']);
            }
        }
            return parent::render($request, $exception);
    }
}
