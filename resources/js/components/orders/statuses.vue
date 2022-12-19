<template>
    <div>
        <span v-if="status == `PROCESSING`" class=" px-3 badge rounded-pill bg-processing text-dark me-3">
            <span class="fa fa-cart-plus fs-7"></span>
            Processing
        </span>
        <span v-if="status == `PENDING_CONFIRMATION`" class=" px-3 badge rounded-pill bg-pending_confirmation text-dark me-3">
            <span class="fa fa-user-plus fs-7"></span>
            Pending Confirmation
        </span>
        <span v-if="status == `PENDING_PICKUP`" class=" px-3 badge rounded-pill bg-pending_pickup text-dark me-3">
            <span class="fa fa-truck-loading fs-7"></span>
            Pending Pickup
        </span>
        <span v-if="status == `SHIPPED`" class=" px-3 badge rounded-pill bg-shipped text-dark me-3">
            <span class="fa fa-shipping-fast fs-7"></span>
            Shipped
        </span>
        <span v-if="status == `DELIVERED`" class=" px-3 badge rounded-pill bg-delivered text-dark me-3">
            <span class="fa fa-box-open fs-7"></span>
            Delivered
        </span>
        <span v-if="status == `CANCELED`" class=" px-3  badge rounded-pill bg-canceled text-dark me-3">
            <span class="fa fa-times-circle fs-7"></span>
            Canceled
        </span>
                        
    </div>
</template>
<script>
export default {
    props:['status']
}
</script>