<?php
declare(strict_types=1);

namespace MerchantOfComplexity\MercurePublisher;

use Assert\Assertion;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpKernel\TerminableInterface;

class MercureAutoDiscover implements TerminableInterface
{
    private string $mercureUrl;

    public function __construct(string $mercureUrl)
    {
        Assertion::notBlank($mercureUrl, 'Mercure url can not be empty');

        $this->mercureUrl = $mercureUrl;
    }

    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    /**
     * @param SymfonyRequest $request
     * @param SymfonyResponse $response
     */
    public function terminate(SymfonyRequest $request, SymfonyResponse $response)
    {
        if (!$response->isRedirection()) {
            $link = sprintf('<%s>; rel="mercure"', $this->mercureUrl);

            $response->headers->set('link', $link);
        }
    }
}
