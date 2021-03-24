<template>
    <v-card>
        <v-card-title>
            {{title}}
        </v-card-title>
        <v-card-text>
            <v-simple-table>
                <template v-slot:default>
                    <thead>
                        <tr>
                            <th class="text-left" v-for="(key, idx) in keys" :key="idx">{{$_.startCase(key[0])}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, sidx) in items" :key="sidx" v-bind="getRoute(item.id)">
                            <td v-for="(key, kidx) in keys" :key="kidx">
                                <div v-html="parseSubItem(item[key[0]], key[1])"></div>
                            </td>
                        </tr>
                    </tbody>
                </template>
            </v-simple-table>
        </v-card-text>
    </v-card>
</template>

<script>
export default {
    name: 'admin-table',
    props: ['items', 'keys', 'routeName', 'title'],
    created(){
        window.onload = function(){
            let links = document.getElementsByClassName('follow-link')
            Array.prototype.forEach.call(links, function(element) {
                element.onclick = () => {
                    window.location = element.getAttribute('href')
                }
            });
        }

    },
    methods: {
        getRoute(id){
            if(this.routeName){
                let m = {}
                m[this.routeName['var']] = id
                return {href: route(this.routeName['route'], m), class: 'follow-link'}
            }
            return {}
        },
        parseSubItem(item, type){
            if(type === 'text'){
                return `<div>${item}</div>`
            }else if(type === 'file'){
                let k = item.split('/')
                let m = k[k.length - 1]
                return `<a href="${item}">${m}</a>`
            }
        }
    }
}
</script>
<style lang="scss">
.follow-link{
    cursor: pointer;
}
</style>
