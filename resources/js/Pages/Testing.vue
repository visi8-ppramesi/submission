<template>
    <div>
        <v-file-input v-model="file" />
        <v-btn @click="submit">asdf</v-btn>
    </div>
</template>

<script>
export default {
    created(){
        axios.post('api/testing/').then((response) => {
            // console.log(response)
            let flat = this.flatten(response.data)
            console.log(this.diffChanges(flat))
        })
    },
    name:'testing',
    data(){
        return {
            file: null
        }
    },
    props:['user'],
    methods: {
        recurveFlatten(arr, obj){
            if(obj.forward){
                obj.forward.submission_items = JSON.parse(obj.forward.submission_items)
                let {forward, ...myObj} = obj.forward
                arr.push(myObj)
                this.recurveFlatten(arr, obj.forward)
            }
        },
        flatten(obj){
            var k = []
            obj.submission_items = JSON.parse(obj.submission_items)
            let {forward, ...myObj} = obj
            k.push(myObj)
            const recurveFlatten = (obj) => {
                if(obj.forward){
                    obj.forward.submission_items = JSON.parse(obj.forward.submission_items)
                    let {forward, ...myObj} = obj.forward
                    k.push(myObj)
                    recurveFlatten(obj.forward)
                }
            }
            recurveFlatten(obj)
            return k
        },
        diffChanges(obj){
            let changes = []
            let len = obj.length
            for(let k = 0; k < len - 1; k++){
                let changed = false
                let myChanges = []
                Object.keys(obj[k].submission_items).forEach((v, i) => {
                    if(obj[k].submission_items[v] !== obj[k + 1].submission_items[v] && v !== 'created_at' && v !== 'updated_at'){
                        changed = true
                        myChanges.push({
                            value: obj[k].submission_items[v] + ' -> ' + obj[k + 1].submission_items[v],
                            name: v
                        })
                    }
                })
                if(changed){
                    changes.push({
                        changes: myChanges,
                        date: obj[k].created_at
                    })

                }
            }
            return changes
        },
        submit(){

        }
    }
}
</script>
