<template>
  <el-badge
      :value="visibleCount"
      :type="badgeType"
      :offset="[-80, 0]"
  >
    <el-button
        style="width: 80px"
        @click="dialogVisible = true"
    >
      Indexes
    </el-button>
  </el-badge>

  <el-dialog
      v-model="dialogVisible"
      width="80%"
      top="10px"
      :append-to-body="true"
      @open="update"
  >
    <template #header>
      <el-space>
        <el-text size="default">
          Dynamic indexes (last 50)
        </el-text>
        <el-input
            v-model="searchText"
            size="small"
            placeholder="Type to search"
            style="width: 300px"
            clearable
        />
        <el-button
            :icon="RefreshIcon"
            size="small"
            @click="update"
            :loading="store.state.loading"
        />
        <el-text>
          Total: {{ totalCount }}, In process: {{ inProcessCount }}, Errors: {{ errorsCount }}
        </el-text>
      </el-space>
    </template>
    <el-table
        :data="indexes"
        style="height: 80vh; width: 100%"
    >
      <el-table-column label="Fields">
        <template #default="props">
          <el-row>
            {{ makeName(props.row) }}
          </el-row>
          <el-row>
            <el-text size="small">
              {{ props.row.name }}
            </el-text>
          </el-row>
        </template>
      </el-table-column>
      <el-table-column label="Status">
        <template #default="props">
          <el-text :type="makeStatusType(props.row)">
            {{ makeStatus(props.row) }}
          </el-text>
        </template>
      </el-table-column>
      <el-table-column label="Created at" fixed="right" min-width="25">
        <template #default="props">
          {{ makeCreatedAt(props.row) }}
        </template>
      </el-table-column>
      <el-table-column fixed="right" min-width="15">
        <template #default="props">
          <el-button
              size="small"
              type="danger"
              link
              @click="deleteIndex(props.row)"
              :loading="deleting[props.row.id]"
          >
            Delete
          </el-button>
        </template>
      </el-table-column>
    </el-table>
  </el-dialog>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import {TraceDynamicIndex, useTraceDynamicIndexesStore} from "../../../../store/traceDynamicIndexesStore.ts";
import {Refresh as RefreshIcon} from "@element-plus/icons-vue";
import {convertDateStringToLocal} from "../../../../utils/helpers.ts";

interface DeletingIndexes {
  [key: string]: boolean,
}

export default defineComponent({
  components: {
    RefreshIcon
  },
  data() {
    return {
      dialogVisible: false,
      store: useTraceDynamicIndexesStore(),
      deleting: {} as DeletingIndexes,
      searchText: '' as String,
    }
  },

  methods: {
    update() {
      this.store.dispatch('findTraceDynamicIndexes')
    },
    updateStats() {
      this.store.dispatch('findTraceDynamicIndexStats')
          .finally(() =>
              setTimeout(() => this.updateStats(), 2000)
          )
    },
    deleteIndex(index: TraceDynamicIndex) {
      if (!confirm('Do you want delete index?')) {
        return
      }

      this.deleting[index.id] = true

      this.store.dispatch('deleteTraceDynamicIndex', {id: index.id})
          .then(() => {
            delete this.deleting[index.id]
          })
    },
    makeName(index: TraceDynamicIndex): string {
      return index.fields.map(index => index.title).join(', ')
    },
    makeCreatedAt(index: TraceDynamicIndex): string {
      return convertDateStringToLocal(index.createdAt, false)
    },
    makeStatus(index: TraceDynamicIndex): string {
      if (index.error) {
        return index.error
      }

      if (index.inProcess) {
        return 'In process'
      }

      return 'active'
    },
    makeStatusType(index: TraceDynamicIndex): 'warning' | 'danger' | 'info' {
      if (index.inProcess) {
        return 'warning'
      }

      if (index.error) {
        return 'danger'
      }

      return 'info'
    },
  },

  computed: {
    indexes(): Array<TraceDynamicIndex> {
      return this.store.state.traceDynamicIndexes.filter(
          (index: TraceDynamicIndex) => {
            if (!this.searchText) {
              return true
            }

            const searchString = this.searchText.toLowerCase();

            return this.makeName(index).toLowerCase().includes(searchString)
                || index.name.toLowerCase().includes(searchString)
          }
      )
    },
    inProcessCount(): number {
      return this.store.state.traceDynamicIndexStats.inProcessCount
    },
    errorsCount(): number {
      return this.store.state.traceDynamicIndexStats.errorsCount
    },
    totalCount(): number {
      return this.store.state.traceDynamicIndexStats.totalCount
    },
    visibleCount(): number {
      if (this.inProcessCount) {
        return this.inProcessCount
      }

      if (this.errorsCount) {
        return this.errorsCount
      }

      return this.totalCount
    },
    badgeType(): 'warning' | 'danger' | 'info' {
      if (this.inProcessCount) {
        return 'warning'
      }

      if (this.errorsCount) {
        return 'danger'
      }

      return 'info'
    },
    RefreshIcon() {
      return RefreshIcon
    },
  },

  mounted() {
    if (!this.store.state.started) {
      this.updateStats()
    }
  }
})
</script>

<style scoped>

</style>
