<?php

namespace Tests\Feature\Common;

use App\Exceptions\Trackers\Sentry;
use Sentry\Tracing\SamplingContext;
use Sentry\Tracing\TransactionContext;
use Tests\Feature\FeatureTestCase;

class SentryTrackerTest extends FeatureTestCase
{
    public function testItShouldNotSampleTracesWhenRateIsUnset()
    {
        config(['sentry.traces_sample_rate' => null]);

        $this->assertSame(0.0, Sentry::tracesSampler($this->samplingContext()));
    }

    public function testItShouldUseConfiguredTracesSampleRate()
    {
        config(['sentry.traces_sample_rate' => 0.25]);

        $this->assertSame(0.25, Sentry::tracesSampler($this->samplingContext()));
    }

    protected function samplingContext(): SamplingContext
    {
        return SamplingContext::getDefault(new TransactionContext());
    }
}
