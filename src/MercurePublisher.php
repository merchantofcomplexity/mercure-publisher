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
        if ($this->queue) {
            $job = new MercureJob($update, $this->queue);

            $this->dispatcher->dispatchToQueue($job);

            return;
        }

        $this->dispatcher->dispatchNow($update);
    }

    public function getQueue(): ?string
    {
        return $this->queue;
    }
}
