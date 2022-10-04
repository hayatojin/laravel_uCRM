<script setup>
import { getToday } from '@/common'
import { onMounted, reactive, ref, computed } from 'vue';
import { Inertia } from '@inertiajs/inertia' 

const props = defineProps({
        'customers' : Array,
        'items' : Array,
    })

// ページ読み込み後 即座に実行
onMounted(()=> { 
    form.date = getToday()

    props.items.forEach( item => {
        itemList.value.push({
            id: item.id,
            name: item.name,
            price: item.price,
            quantity: 0
        })
    })
})

// props内の変数は、変更できない。なので、別で空の配列を用意
// 配列にはreactiveは使えないため、refを使う
const itemList = ref([]) 

// 動的に変化させる（選択される内容によって変化させる）項目の初期値設定
const form = reactive({
    date: null,
    customer_id: null,
    status: true,
    items: []
})

const quantity = [ "0", "1", "2", "3", "4", "5", "6", "7", "8", "9"] // option用

// 合計金額の計算処理
const totalPrice = computed(() => {
    let total = 0
    itemList.value.forEach( item => {
        total += item.price * item.quantity
    })
    return total
})

const storePurchase = () => {
    itemList.value.forEach( item => {
        if( item.quantity > 0){
            form.items.push({
                id: item.id,
                quantity: item.quantity
            })
        }
    })
    Inertia.post(route('purchases.store'), form )
}

// const setCustomerId = id => {
//   form.customer_id = id
// }

</script>


<template>
    <form @submit.prevent = "storePurchase">
    日付<br>
    <input type="date" name="date" v-model="form.date"><br>

    会員名<br>
    <select name="customer" v-model="form.customer_id">
        <option v-for="customer in customers" :value="customer.id" :key="customer.id">
            {{ customer.id }} : {{ customer.name }}
        </option>
    </select>
    <br>

    商品・サービス<br>
    <table>
        <thead>
            <tr>
                <th>Id</th>
                <th>商品名</th>
                <th>金額</th>
                <th>数量</th>
                <th>小計</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in itemList" >
                <td>{{ item.id }}</td>
                <td>{{ item.name }}</td>
                <td>{{ item.price }}</td>
                <td>
                    <select name="quantity" v-model="item.quantity">
                        <option v-for="q in quantity" :value="q">{{ q }}</option>
                    </select>
                </td>
                <td>
                    <!-- 小計 -->
                    {{ item.price * item.quantity }}
                </td>
            </tr>
        </tbody>
    </table>
    <br>
    合計: {{ totalPrice }}円<br>
    <button>登録する</button>
    </form>
</template>