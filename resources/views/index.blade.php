<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vue Resource - CRUD USER</title>
</head>
<body>
    <div id="app">
        <input v-model="name">
        <button @click="addUser" v-show="!show">Add</button>
        <button @click="updateUser" v-show="show">Update</button><br>
        <ul :style="styleUl">
            <user-template
                v-for="(user, index) in users"
                :user="user"
                :index="index"
                :key="index"
                @edited="editedUser"
                @deleted="deletedUser"
            ></user-template>            
        </ul>
    </div>

    
    <script src="{{ url('/script-vue/vue.js') }}"></script>
    <script src="{{ url('/script-vue/vue-resource.js') }}"></script>
    <script>
        Vue.component('user-template', {
            props: ['user', 'index'],
            template: `
                <li>
                    @{{ user.name }}
                    <button @click="editMethod(user, index)">Edit</button>
                    <button @click="deleteMethod(user.id, index)">Delete</button>
                    <br>
                </li>
            `,
            methods: {
                editMethod(obj, index) {
                    this.$emit('edited', obj, index)
                },
                deleteMethod(id, index) {
                    var z = confirm("Anda Yakin?")
                    if (z == true) {
                        this.$emit('deleted', id, index)
                    }
                }
            }
        });

        new Vue({
            el: "#app",
            data: {
                name: '',
                users: [],
                show: false,
                tempIndex: 0,
                tempId: 0,
                styleUl: 'list-style-type: none;'
            },
            methods: {
                addUser() {
                    let textInput = this.name;
                    if(textInput) {
                        this.$http.post('/api/user', {name: textInput}).then(response => {
                            this.users.push(
                                { name: textInput }
                            )
                            this.name = '';
                            this.show = false
                        });
                    }
                },
                updateUser() {
                    this.$http.post('/api/user/update-name',
                        { name: this.name, id: this.tempId }
                    ).then(response => {
                        this.users[this.tempIndex].name = this.name
                        this.name = ''
                        this.tempId = 0
                        this.show = false
                    });
                },
                editedUser(user, index) {
                    this.name = user.name
                    this.tempIndex = index
                    this.tempId = user.id
                    this.show = true
                },
                deletedUser(id, index) {
                    this.$http.post('/api/user/delete/' + id).then(response => {
                        this.users.splice(index, 1);
                    });
                    this.show = false
                    this.name = ''
                }
            },
            mounted() {
                this.$http.get('/api/user').then(response => {
                    // get body data
                    let result = response.body.data
                    this.users = result
                });
            }
        })
    </script>
</body>
</html>