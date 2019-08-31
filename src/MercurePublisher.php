<?php
declare(strict_types=1);

namespace MerchantOfComplexity\MercurePublisher;

use Illuminate\Contracts\Bus\QueueingDispatcher;
use Symfony\Component\Mercure\Update;

class MercurePublisher
{
    private QueueingDispatcher $dispatcher;
    private ?string $queue;

    public function __construct(QueueingDispatcher $dispatcher, ?string $queue)
    {
        $this->dispatcher = $dispatcher;
        $this->queue = $queue;
    }

    public function __invoke(Update $update)
    {
        $job = new MercureJob($update, $this->queue);

        $this->queue
            ? $this->dispatcher->dispatchToQueue($job)
            : $this->dispatcher->dispatchNow($job);
    }

    public function getQueue(): ?string
    {
        return $this->queue;
    }
}
