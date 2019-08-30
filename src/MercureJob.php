<?php
declare(strict_types=1);

namespace MerchantOfComplexity\MercurePublisher;

use Illuminate\Contracts\Queue\Queue as IlluminateQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Symfony\Component\Mercure\Publisher as SymfonyPublisher;
use Symfony\Component\Mercure\Update as SymfonyUpdate;

class MercureJob implements ShouldQueue
{
    private SymfonyUpdate $update;
    private ?string $queue;

    public function __construct(SymfonyUpdate $update, ?string $queue)
    {
        $this->update = $update;
        $this->queue = $queue;
    }

    public function queue(IlluminateQueue $queue, MercureJob $mercureJob): void
    {
        $queue->pushOn($this->queue, $mercureJob);
    }

    public function handle(SymfonyPublisher $publish)
    {
        $publish($this->update);
    }
}
