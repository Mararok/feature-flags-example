<?php

namespace App\Util\FeatureFlag;

use Growthbook\FeatureResult;
use Growthbook\Growthbook;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

abstract class GrowthbookFeatureFlags
{
    protected Growthbook $gb;
    protected int $currentTimestamp = 0;

    public function __construct(private string $cacheFile, private string $webhookSecret, private string $endpoint)
    {
        $this->gb = Growthbook::create();
        $this->reloadFromCache();
    }

    public function reloadFromCache(): void
    {
        $cached = $this->getRawCached();
        $this->currentTimestamp = $cached["timestamp"] ?? 0;
        $this->gb->withFeatures($cached["features"]);
    }

    public function refreshCache(): void {
        $response = json_decode(file_get_contents($this->endpoint), true, JSON_THROW_ON_ERROR);
        $cacheData = [
            "timestamp" => strtotime($response["dateUpdated"]),
            "dateUpdated" => $response["dateUpdated"],
            "features" => $response["features"]
        ];

        file_put_contents($this->cacheFile, json_encode($cacheData));
    }

    public function replaceFromWebhook(string $payload, $signature)
    {
        if (!$this->isValidWebhookPayload($payload, $signature)) {
            throw new BadRequestException("invalid payload");
        }

        $decodedData = json_decode($payload, true, JSON_THROW_ON_ERROR);
        $rawCached = $this->getRawCached();
        if ($rawCached["timestamp"] > $decodedData["timestamp"]) {
            return;
        }

        file_put_contents($this->cacheFile, $payload);
    }

    public function getRawCached(): array
    {
        return file_exists($this->cacheFile) ? \json_decode(file_get_contents($this->cacheFile), true) : ["timestamp" => 0, "features" =>[]];
    }

    private function isValidWebhookPayload(string $payload, string $verify): bool
    {
        $computed = $this->computeWebhookPayloadSignature($payload);
        return hash_equals($verify, $computed);
    }

    private function computeWebhookPayloadSignature(string $payload): string
    {
        return hash_hmac('sha256', $payload, utf8_encode($this->webhookSecret));
    }

    public function getAttributes(): array
    {
        return $this->gb->getAttributes();
    }

    public function isOn($id): bool
    {
        return $this->getFeature($id)->on;
    }

    public function isOff($id): bool
    {
        return $this->getFeature($id)->off;
    }

    protected function getFeature($id): FeatureResult
    {
        return $this->gb->getFeature(self::featureIdToString($id));
    }

    public static function featureIdToString($id): string
    {
        return ($id instanceof \UnitEnum) ? $id->name : $id;
    }
}
