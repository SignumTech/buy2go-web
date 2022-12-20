<template>
    <div class="row p-4">
        <div class="col-md-12 d-flex justify-content-center p-4">
            <qr-code :size="500" :text="id"></qr-code>
        </div>
    </div>
</template>
<script>
export default {
    props:['id']
}
</script>