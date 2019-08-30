<?php
declare(strict_types=1);

namespace MerchantOfComplexity\MercurePublisher;

use Illuminate\Contracts\Bus\QueueingDispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;
use Symfony\Component\Mercure\Publisher as SymfonyPublisher;

class MercurePublisherServiceProvider extends ServiceProvider
{
    const MERCURE_CONFIG = 'mercure_publisher';

    /**
     * @var bool
     */
    protected $defer = true;

    /**
     * @param Kernel|\Illuminate\Foundation\Http\Kernel $kernel
     */
    public function boot(Kernel $kernel): void
    {
        $this->publishMercureConfiguration();

        $config = $this->app->get('config')->get(self::MERCURE_CONFIG);

        if (empty($config)) {
            throw new InvalidArgumentException("Unable to load Mercure publisher configuration");
        }

        if ($autoDiscover = $config['auto_discover'] ?? false) {
            $kernel->pushMiddleware(MercureAutoDiscover::class);
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom($this->getConfigPath(), self::MERCURE_CONFIG);

        $this->app->bind(MercurePublisher::class, function (Application $app): MercurePublisher {
            $queue = $app->get('config')->get('mercure_publisher')['queue'] ?? null;

            return new MercurePublisher($app->get(QueueingDispatcher::class), $queue);
        });

        $this->app->bind(SymfonyPublisher::class, function (Application $app) {
            $config = $app->get('config')->get('mercure_publisher');

            $jwtProvider = $config['callable_kwt_provider'];

            return new SymfonyPublisher($config['hub'], $app->make($jwtProvider));
        });
    }

    public function provides(): array
    {
        return [MercurePublisher::class, SymfonyPublisher::class];
    }

    protected function publishMercureConfiguration(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [$this->getConfigPath() => config_path(self::MERCURE_CONFIG . '.php')],
                'config'
            );
        }
    }

    protected function getConfigPath(): string
    {
        return __DIR__ . '/../config/' . self::MERCURE_CONFIG . '.php';
    }
}
