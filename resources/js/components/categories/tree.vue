<template>
    <select class="form-select">
        <node-tree v-for="tr,index in treeData" :key="index" :node="tr"></node-tree>
    </select>
</template>

<script>
import NodeTree from "./NodeTree.vue";

export default {
props: ["treeData"],
components: {
    NodeTree
}
};
</script>
  
