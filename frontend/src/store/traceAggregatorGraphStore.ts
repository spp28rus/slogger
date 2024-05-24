import type {InjectionKey} from "vue";
// @ts-ignore // todo
import {createStore, Store, useStore as baseUseStore} from 'vuex'
import {AdminApi} from "../api-schema/admin-api-schema.ts";
import {ApiContainer} from "../utils/apiContainer.ts";
import {convertDateStringToLocal, handleApiError} from "../utils/helpers.ts";
import {ChartData, ChartOptions} from 'chart.js'

type TraceAggregatorTraceMetricsPayload = AdminApi.TraceAggregatorTraceMetricsCreate.RequestBody
type TraceAggregatorTraceMetricResponse = AdminApi.TraceAggregatorTraceMetricsCreate.ResponseBody
type TraceAggregatorTraceMetricItem = AdminApi.TraceAggregatorTraceMetricsCreate.ResponseBody['data']['items'][number]

type TraceAggregatorTraceTimestampPeriod = AdminApi.TraceAggregatorTraceMetricsCreate.RequestBody['timestamp_period']

interface State {
    showGraph: boolean,
    loading: boolean,

    metrics: Array<TraceAggregatorTraceMetricItem>,
    payload: TraceAggregatorTraceMetricsPayload,

    graphData: ChartData,
    graphOptions: ChartOptions,

    preTimestamp: string | null,
    preTimestampCounts: number,
}

export const traceAggregatorGraphStore = createStore<State>({
    state: {
        showGraph: false,
        loading: false,

        metrics: new Array<TraceAggregatorTraceMetricItem>,
        payload: {} as TraceAggregatorTraceMetricsPayload,

        graphData: {
            labels: [],
            datasets: []
        } as ChartData,
        graphOptions: {
            responsive: true,
            maintainAspectRatio: false,
            animation: false,
            scales: {
               x: {
                   grid: {
                       color: 'rgba(121,146,248,0.1)'
                   }
               },
               y: {
                   grid: {
                       color: 'rgba(121,146,248,0.1)'
                   }
               },
            }
        } as ChartOptions,

        preTimestamp: null
    } as State,
    mutations: {
        setMetrics(state: State, data: TraceAggregatorTraceMetricResponse) {
            state.metrics = data.data.items

            if (!state.metrics.length) {
                state.graphData = {
                    labels: [],
                    datasets: [],
                }

                return
            }

            const timestamp = state.metrics[state.metrics.length - 1].timestamp;

            const totalCount = state.metrics.reduce((a, b) => {
                return a + b.count;
            }, 0)

            if (state.preTimestamp === timestamp
                && totalCount === state.preTimestampCounts
            ) {
                return;
            }

            state.preTimestamp = timestamp
            state.preTimestampCounts = totalCount

            const labels: Array<string> = []
            const datasetData: Array<number> = []

            state.metrics.forEach((item: TraceAggregatorTraceMetricItem) => {
                labels.push(
                    convertDateStringToLocal(item.timestamp, false)
                )
                datasetData.push(
                    item.count
                )
            })

            state.graphData = {
                labels: labels,
                datasets: [
                    {
                        label: 'graph',
                        backgroundColor: 'rgba(163,248,121,0.7)',
                        data: datasetData
                    },
                ],
            }
        },
        setTimestampPeriod(state: State, timestampPeriod: TraceAggregatorTraceTimestampPeriod) {
            state.payload.timestamp_period = timestampPeriod
        }
    },
    actions: {
        async findMetrics({commit, state}: { commit: any, state: State }) {
            state.loading = true

            try {
                const response = await ApiContainer.get().traceAggregatorTraceMetricsCreate(state.payload)

                commit('setMetrics', response.data)
            } catch (error) {
                handleApiError(error)
            } finally {
                state.loading = false
            }
        },
        setTimestampPeriod({commit}: { commit: any }, timestampPeriod: TraceAggregatorTraceTimestampPeriod) {
            commit('setTimestampPeriod', timestampPeriod)
        }
    },
})

export const traceAggregatorGraphStoreInjectionKey: InjectionKey<Store<State>> = Symbol()

export function useTraceAggregatorGraphStore(): Store<State> {
    return baseUseStore(traceAggregatorGraphStoreInjectionKey)
}