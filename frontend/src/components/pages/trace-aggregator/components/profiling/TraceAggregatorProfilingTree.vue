<template>
  <div style="overflow-y: scroll; width: 100%; height: 85vh">
    <el-tree
        :props="treeProps"
        node-key="key"
        :expand-on-click-node="false"
        style="width: 95vw"
        :load="loadNode"
        lazy
    >
      <template #default="{ node }">
        <el-row :class="isSelectedNode(node) ? 'selected-node' : ''" style="width: 100%">
          <el-text
              :class="isInHardestFlow(node) ? 'node-flow-hardest-flow' : ''" style="padding-right: 5px; font-size: 13px"
              truncated
          >
            {{ node.label }}
          </el-text>
          <el-space spacer="|">
            <el-button type="info" @click="onShowFlow(node)" link>
              flow
            </el-button>
            <el-button type="info" @click="onCalculateMetrics(node)" link>
              hardest
            </el-button>
            <div>
              <TraceAggregatorProfilingNodeMetrics :item="findItemByNode(node)"/>
            </div>
          </el-space>
        </el-row>
      </template>
    </el-tree>
  </div>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import {
  ProfilingItem,
  ProfilingTreeNode,
  useTraceAggregatorProfilingStore
} from "../../../../../store/traceAggregatorProfilingStore.ts";
import {ProfilingItemFinder} from "./utils/itemFinder.ts";
import TraceAggregatorProfilingNodeData from "./TraceAggregatorProfilingNodeData.vue";
import TraceAggregatorProfilingNodeMetrics from './TraceAggregatorProfilingNodeMetrics.vue'
import {TreeFilter} from "./utils/treeFilter.ts";
import type Node from 'element-plus/es/components/tree/src/model/node'
import {ProfilingTreeNodeBuilder} from "./utils/treeNodeBuilder.ts";

export default defineComponent({
  components: {TraceAggregatorProfilingNodeData, TraceAggregatorProfilingNodeMetrics},

  data() {
    return {
      store: useTraceAggregatorProfilingStore(),
      treeProps: {
        children: 'children',
        label: 'label',
        disabled: 'disabled',
        isLeaf: 'leaf',
      },
      finder: new TreeFilter()
    }
  },

  methods: {
    loadNode(treeNode: Node, resolve: (data: ProfilingTreeNode[]) => void) {
      if (treeNode.level === 0) {
        const foundItem = (new ProfilingItemFinder()).findByCalling(
            this.store.state.profiling.main_caller,
            this.store.state.profiling.items
        )

        if (!foundItem) {
          resolve([])

          return
        }

        resolve([
          {
            key: foundItem.id,
            label: foundItem.calling,
            disabled: false,
            leaf: false
          }
        ])

        return
      }

      const foundItem = (new ProfilingItemFinder()).findByCalling(
          treeNode.data.label,
          this.store.state.profiling.items
      )

      if (!foundItem) {
        resolve([])

        return
      }

      const data = (new ProfilingTreeNodeBuilder()).build(
          foundItem.calling,
          this.store.state.profiling.items
      )

      resolve(data)
    },
    onShowFlow(node: ProfilingTreeNode) {
      if (node.label === this.store.state.selectedItem?.calling) {
        this.store.dispatch('setSelectedProfilingItem', null)
      } else {
        const foundItem = (new ProfilingItemFinder()).findByCalling(
            node.label,
            this.store.state.profiling.items
        )

        this.store.dispatch('setSelectedProfilingItem', foundItem)
      }
    },
    isSelectedNode(node: ProfilingTreeNode): boolean {
      return node.label === this.store.state.selectedItem?.calling
    },
    isInHardestFlow(node: ProfilingTreeNode): boolean {
      return this.store.state.profilingMetrics.hardestItemIds.indexOf(node.key) !== -1
    },
    onCalculateMetrics(node: ProfilingTreeNode) {
      this.store.dispatch('calculateProfilingMetrics', node)
    },
    findItemByNode(node: ProfilingTreeNode): ProfilingItem | null {
      return (new ProfilingItemFinder()).findById(
          node.key,
          this.store.state.profiling.items
      )
    },
  },
})
</script>

<style scoped>
.selected-node {
  font-weight: bold;
}

.node-flow-hardest-flow {
  color: red;
}
</style>
