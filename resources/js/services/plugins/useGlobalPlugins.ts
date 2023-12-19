import { getCurrentInstance } from 'vue';

export function useGlobalPlugins() {
    const app = getCurrentInstance();

    if (!app || !app.appContext || !app.appContext.app) {
        return {
            $api: null,
            $endPoints: null,
        };
    }

    return {
        $api: app.appContext.config.globalProperties.$api,
        $endPoints: app.appContext.config.globalProperties.$endPoints,
    };
}
