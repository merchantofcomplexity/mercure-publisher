<?php
declare(strict_types=1);

namespace MerchantOfComplexity\MercurePublisher;

use Assert\Assertion;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\TerminableInterface;

class MercureAutoDiscover implements TerminableInterface
{
    private string $mercureUrl;

    public function __construct(string $mercureUrl)
    {
        Assertion::notBlank($mercureUrl, 'Mercure url can not be empty');

        $this->mercureUrl = $mercureUrl;
    }

    public function terminate(Request $request, Response $response)
    {
        if (!$response->isRedirection()) {
            $link = sprintf('<%s>; rel="mercure"', $this->mercureUrl);

            $response->headers->set('link', $link);
        }
    }
}
