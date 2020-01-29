<template>    
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h4 class="font-bold">Order Table</h4>
            </div>
            <div class="col-md-12">
                <table class="table">
                    <thead>
                        <th v-for="header in headers">
                            {{header}}
                        </th>
                    </thead>
                    <tbody>
                        <tr v-for="row in data">
                            <td>{{row.id}}</td>
                            <td>{{row.order_type}}</td>
                            <td>{{row.status}}</td>
                            <td>{{row.created_at}}</td>
                            <td>
                                <a :href="'order/'+row.id">View</a>
                                <a :href="'order/'+row.id+'/edit'">Edit</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data:function(){
            return {
                headers:["Order Id","Order Type","Status","Created At","Actions"],
                data:[]
            }
        },
        created: function () {
            axios.get('api/orders/getAll')
            .then(response => {
                console.log(response.data);
                this.data=response.data;
            });
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>