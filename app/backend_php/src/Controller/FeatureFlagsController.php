<?php
namespace App\Controller;

use App\AppFeatureFlags;
use App\FF;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeatureFlagsController
{
    public function __construct(private AppFeatureFlags $ff)
    {
    }

    #[Route('/system/feature-flags', methods: ["POST"])]
    public function replace(Request $rq): Response
    {
        $payload = $rq->getContent();
        $signature = $rq->headers->get("X-GrowthBook-Signature");
        $this->ff->replaceFromWebhook($payload, $signature);

        return new JsonResponse(null, 204);
    }

    #[Route('/system/feature-flags', methods: ["GET"])]
    public function getList(): Response
    {
        $flags = $this->ff->getRawCached();

        return JsonResponse::fromJsonString(json_encode(["env" => getenv("APP_ENV"), "flags" => $flags]));
    }

    #[Route('/feature1', methods: ["GET"])]
    public function feature1(Request $rq): Response
    {
        $data = "normal_data";
        if ($this->ff->isOn(FF::feature_1)) {
            $data = "feature1_data";
        }

        return new JsonResponse(["env" => getenv("APP_ENV"), "data" => $data]);
    }

    #[Route('/feature2', methods: ["GET"])]
    public function feature2(Request $rq): Response
    {
        $data = "normal_data";

        $this->ff->setAttributes($rq->cookies->get("QA", 0));
        if ($this->ff->isOn(FF::feature_2)) {
            $data = "feature2_data";
        }

        return new JsonResponse(["env" => getenv("APP_ENV"),"ff_attributes" => $this->ff->getAttributes(), "data" => $data]);
    }

}
