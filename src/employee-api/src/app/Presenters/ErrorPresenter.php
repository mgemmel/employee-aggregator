<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Exceptions\MethodNotAllowedException;
use InvalidArgumentException;
use Nette;
use Nette\Application\BadRequestException;
use Nette\Application\Responses;
use Nette\Http;
use Tracy\ILogger;


final class ErrorPresenter implements Nette\Application\IPresenter
{
	use Nette\SmartObject;

	public function __construct(
		private ILogger $logger,
        private Http\Response $response
	) {
	}


	public function run(Nette\Application\Request $request): Nette\Application\Response
	{
		$exception = $request->getParameter('exception');
        $response = [
            'error' => 'Internal server error'
        ];

        if ($exception instanceof InvalidArgumentException ||
            $exception instanceof BadRequestException ||
            $exception instanceof MethodNotAllowedException
        ){
            $this->response->setCode(400);
            $response['error'] = $exception->getMessage();
            return new Responses\JsonResponse($response);
        }

        $this->response->setCode(500);

        return new Responses\JsonResponse($response);
	}
}
