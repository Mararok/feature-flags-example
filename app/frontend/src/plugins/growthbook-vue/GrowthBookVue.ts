import { configureCache, GrowthBook } from "@growthbook/growthbook";
import { inject } from "vue";

export type FeatureFlagFunction = (id: string) => boolean;

export function useFeatureFlag(): FeatureFlagFunction {
    return inject("FEATURE_FLAG") as FeatureFlagFunction;
}

export const FeatureFlagRefreshEventType = "feature-flag-refresh";

export function addFeatureFlagRefreshEventListener(callback: () => void) {
    window.addEventListener(FeatureFlagRefreshEventType, callback);
}

export interface GrowthBookVueOptions {
    apiHost: string;
    clientKey: string;
    autoRefresh?: boolean;
    timeout?: number;
}

window.getCookie = function (name: string) {
    const match = document.cookie.match(new RegExp("(^| )" + name + "=([^;]+)"));
    if (match) return match[2];
};

export const GrowthBookVue = async (opts: GrowthBookVueOptions) => {


    const gb = new GrowthBook({
        enableDevMode: false,
        apiHost: opts.apiHost,
        clientKey: opts.clientKey,
        trackingCallback: (experiment, result) => {
            console.log({
                experimentId: experiment.key,
                variationId: result.variationId,
            });
        },
        attributes: {
            QA: window.getCookie("QA") === "1",
        },
        onFeatureUsage: (featureKey, result) => {
            if (result.on) {
                console.log("Using feature: ", featureKey);
            }
        },
    });

    function hashCode(s: string) {
        for (var i = 0, h = 0; i < s.length; i++) {
            h = (Math.imul(31, h) + s.charCodeAt(i)) | 0;
        }
        return h;
    }

    configureCache({ staleTTL: 1000 * 2 });

    let lastHash: any = null;
    await gb.refreshFeatures({ skipCache: true, timeout: opts.timeout });

    const refresh = async () => {
        await gb.refreshFeatures({ timeout: opts.timeout });
        setTimeout(refresh, 2000);
    };
    setTimeout(refresh, 2000);

    gb.setRenderer(() => {
        if (lastHash === null) {
            lastHash = hashCode(JSON.stringify(gb.getFeatures()));
            return;
        }

        const current = hashCode(JSON.stringify(gb.getFeatures()));
        console.log(current, lastHash);
        if (current !== lastHash) {
            lastHash = current;
            window.dispatchEvent(new Event(FeatureFlagRefreshEventType));
        }
    });

    return {
        install(app: any, options: any) {
            const ff: FeatureFlagFunction = (id: string): boolean => gb.isOn(id);
            app.config.globalProperties.FEATURE_FLAG = ff;
            app.provide("growthbook", gb);
            app.provide("FEATURE_FLAG", ff);
        },
    };
};
