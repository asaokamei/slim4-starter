<?php
/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim-Csrf/blob/master/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace App\Application\Middleware;

use Slim\Csrf\Guard;

class CsRfGuard extends Guard
{
    public function clearLastTokenFromRequest()
    {
        $token = $this->getTokenName();
        $this->removeTokenFromStorage($token);
    }
}
