<?php

namespace App;
use App\Util\FeatureFlag\GrowthbookFeatureFlags;


class AppFeatureFlags extends GrowthbookFeatureFlags
{
    public function setAttributes(bool $qa): void
    {
        $this->gb->withAttributes(["QA" => $qa]);
    }

    /**
     * @param FF $id
     */
    public function isOn($id): bool
    {
        return parent::isOn($id);
    }

    /**
     * @param FF $id
     */
    public function isOff($id): bool
    {
        return parent::isOff($id);
    }


}
