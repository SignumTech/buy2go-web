<template>
    <optgroup :label="node.parent" value="1">
        <option v-if="node.children && node.children.length" :value="node.id">
            {{node.cat_name}}
            <node v-for="child,index in node.children" :node="child" :key="index"></node>
        </option>
    </optgroup>
</template>
  
<script>
export default {
name: "node",
props: {
    node: Object
}
};
</script>