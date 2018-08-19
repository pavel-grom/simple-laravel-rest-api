<template>
    <div class="container">
        <h1 class="text-center" v-if="$auth.check()">Items</h1>
        <div class="text-center">
            <button v-if="$auth.check()" type="button" class="btn btn-primary text-center create_btn" data-toggle="modal" data-target="#modalCreate">
                Create item
            </button>
        </div>
        <table class="table table-bordered" v-if="$auth.check()">
            <thead>
                <th>Name</th>
                <th>Key</th>
                <th>Created</th>
                <th>Updated</th>
                <th></th>
                <th></th>
                <th></th>
            </thead>
            <tbody>
                <tr v-for="(item, index) in items">
                    <td>{{ item.name }}</td>
                    <td>{{ item.key }}</td>
                    <td>{{ item.created_at }}</td>
                    <td>{{ item.updated_at }}</td>
                    <td>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal" @click="getChanges(item.id)">
                            View changes
                        </button>
                    </td>
                    <td>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalEdit" @click="assignEditItem(item, index)">
                            Edit
                        </button>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger" data-toggle="modal" @click="delete_item(item.id, index)">
                            Delete
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <h2 v-else class="text-center">
            <router-link :to="{ name: 'login' }">
                <a class="link">Login</a>
            </router-link>
            or
            <router-link :to="{ name: 'register' }">
                <a class="link">register</a>
            </router-link>
            for view items list.
        </h2>

        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">Item changes</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered" v-if="item_history && item_history.length > 1">
                            <thead>
                                <th>Name</th>
                                <th>Key</th>
                                <th>Date</th>
                            </thead>
                            <tbody>
                                <tr v-for="(change, index) in item_history">
                                    <td v-if="index">{{ item_history[index - 1].name }} -> {{ change.name }}</td>
                                    <td v-if="index">{{ item_history[index - 1].key }} -> {{ change.key }}</td>
                                    <td v-if="index">{{ change.created_at }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <h4 v-else>This item doesn't have any changes</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="ModalCreateLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalCreateLabel">Create item</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="create_name">Name</label>
                                <input type="text" class="form-control" id="create_name" v-bind:class="{ 'is-invalid': create_errors && create_errors.name }" v-model="create_item.name">
                                <div class="invalid-feedback" v-if="create_errors && create_errors.name" v-for="error in create_errors.name">{{ error }}</div>
                            </div>
                            <div class="form-group">
                                <label for="create_key">Key</label>
                                <input type="text" class="form-control" id="create_key" v-bind:class="{ 'is-invalid': create_errors && create_errors.key }" v-model="create_item.key">
                                <div class="invalid-feedback" v-if="create_errors && create_errors.key" v-for="error in create_errors.key">{{ error }}</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" @click="create">Create</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="ModalEditLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form v-if="edit_item">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalEditLabel">Edit item</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" v-bind:class="{ 'is-invalid': edit_errors && edit_errors.name }" id="name" v-model="edit_item.name">
                                <div class="invalid-feedback" v-if="edit_errors && edit_errors.name" v-for="error in edit_errors.name">{{ error }}</div>
                            </div>
                            <div class="form-group">
                                <label for="key">Key</label>
                                <input type="text" class="form-control" id="key" v-bind:class="{ 'is-invalid': edit_errors && edit_errors.key }" v-model="edit_item.key">
                                <div class="invalid-feedback" v-if="edit_errors && edit_errors.key" v-for="error in edit_errors.key">{{ error }}</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" @click="edit">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            if (!this.$auth.check()) {
                return;
            }

            var app = this;
            axios.get('/api/items')
                .then(function (res) {
                    app.items = res.data.data;
                })
                .catch(function (res) {
                    console.log(res);
                    alert("Error on load.");
                });
        },
        data(){
            return {
                items: null,
                item_history: null,
                create_item: {name: '', key: ''},
                create_errors: [],
                edit_item: null,
                edited_item: null,
                edit_item_index: null,
                old_item: null,
                edit_errors: []
            }
        },
        methods: {
            getChanges: function(item_id) {
                var app = this;
                axios.get('/api/items/' + item_id + '/history')
                    .then(function (res) {
                        app.item_history = res.data.data;
                        console.log(app.item_history);
                    })
                    .catch(function (res) {
                        console.log(res);
                        alert("Error on load.");
                    });
            },
            create: function() {
                var app = this;
                axios.post('/api/items', this.create_item)
                    .then(function (res) {
                        app.items.push(res.data.data);
                        app.create_errors = [];
                        app.create_item =  {name: '', key: ''};
                        $('#modalCreate').modal('hide');
                    })
                    .catch(function (error) {
                        app.create_errors = error.response.data.errors;
                        console.log(app.edit_errors);
                    });
            },
            edit: function() {
                var app = this;
                axios.put('/api/items/' + this.edit_item.id, this.edit_item)
                    .then(function (res) {
                        app.edited_item.name = res.data.data.name;
                        app.edited_item.key = res.data.data.key;
                        app.edited_item.updated_at = res.data.data.updated_at;
                        app.edit_errors = [];
                        $('#modalEdit').modal('hide');
                    })
                    .catch(function (error) {
                        app.edit_errors = error.response.data.errors;
                        console.log(app.edit_errors);
                    });
            },
            delete_item: function(item_id, index){
                var app = this;
                axios.post('/api/items/' + item_id, {_method: 'delete'})
                    .then(function (res) {
                        app.$delete(app.items, index);
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            assignEditItem: function(item, index) {
                this.edit_item = Object.assign({}, item);
                this.edited_item = item;
                this.edit_item_index = index;
                this.edit_errors = [];
            }
        },

    }
</script>