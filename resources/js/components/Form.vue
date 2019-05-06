<template>
    <div>
        <v-toolbar
        card
        color="primary"
        class="secondary--text"
        dark
        >
            <v-toolbar-title>Новое лекарственное средство</v-toolbar-title>
        </v-toolbar>
        <v-form action="" method="post">
            <v-container>
                <v-layout row wrap>
                    <v-flex xs12 md6>
                        <v-autocomplete
                        v-model="mnn"
                        :items="mnns"
                        :loading="mnnIsLoading"
                        :search-input.sync="mnnSearch"
                        box
                        hide-no-data
                        hide-selected
                        item-text="name"
                        item-value="id"
                        label="МНН"
                        placeholder="Начните вводить для поиска"
                        return-object
                        dense
                        ref="mnn"
                        :rules="[() => !!mnn || 'Обязательное поле']"
                        ></v-autocomplete>
                    </v-flex>
                    <v-flex xs12 md6>
                        <v-select
                        v-model="form"
                        :items="forms"
                        :loading="formIsLoading"
                        :placeholder="formPlaceholder"
                        return-object
                        item-text="name"
                        item-value="id"
                        name="form"
                        label="Форма"
                        :disabled="formDisabled"
                        box
                        ref="form"
                        :rules="[() => !!form || 'Обязательное поле']"
                        ></v-select>

                    </v-flex>
                    <v-flex xs12 md4>
                        <v-text-field
                        v-model="doza"
                        name="doza"
                        type="text"
                        label="Доза"
                        box
                        ref="doza"
                        :rules="[() => !!doza || 'Обязательное поле']"
                        ></v-text-field>
                    </v-flex>
                    <v-flex xs12 md4>
                        <v-text-field
                        v-model="opu"
                        name="opu"
                        type="text"
                        label="ОПУ"
                        box
                        ref="opu"
                        :rules="[() => !!opu || 'Обязательное поле']"
                        ></v-text-field>
                    </v-flex>
                    <v-flex xs12 md4>
                        <v-text-field
                        v-model="thchp"
                        name="thchp"
                        type="text"
                        label="ТХЧП"
                        box
                        ref="thchp"
                        :rules="[() => !!thchp || 'Обязательное поле']"
                        ></v-text-field>
                    </v-flex>
                    <v-flex xs12 md4>
                        <v-select
                        v-model="perviy_god"
                        :items="years"
                        name="perviy_god"
                        label="Первый год"
                        box
                        ref="perviy_god"
                        :rules="[() => !!perviy_god || 'Обязательное поле']"
                        ></v-select>
                    </v-flex>
                    <v-flex xs12 md4>
                        <v-text-field
                        v-model="summa_prodazh_za_perviy_god"
                        name="summa_prodazh_za_perviy_god"
                        type="text"
                        label="Сумма продаж за первый год"
                        :placeholder="perviy_god_children_disabled?'Выберите первый год':''"
                        :disabled="perviy_god_children_disabled"
                        box
                        ref="summa_prodazh_za_perviy_god"
                        :rules="[() => !!summa_prodazh_za_perviy_god || 'Обязательное поле']"
                        ></v-text-field>
                    </v-flex>
                    <v-flex xs12 md4>
                        <v-text-field
                        v-model="prodazhi_upakovok_za_perviy_god"
                        name="prodazhi_upakovok_za_perviy_god"
                        type="text"
                        label="Продажы упаковок за первый год"
                        :placeholder="perviy_god_children_disabled?'Выберите первый год':''"
                        :disabled="perviy_god_children_disabled"
                        box
                        ref="prodazhi_upakovok_za_perviy_god"
                        :rules="[() => !!prodazhi_upakovok_za_perviy_god || 'Обязательное поле']"
                        ></v-text-field>
                    </v-flex>
                    <v-flex xs12 md4>
                        <v-select
                        v-model="vtoroi_god"
                        :items="years"
                        name="vtoroi_god"
                        label="Второй год"
                        box
                        ref="vtoroi_god"
                        :rules="[() => !!vtoroi_god || 'Обязательное поле']"
                        ></v-select>
                    </v-flex>
                    <v-flex xs12 md4>
                        <v-text-field
                        v-model="summa_prodazh_za_vtoroi_god"
                        name="summa_prodazh_za_vtoroi_god"
                        type="text"
                        label="Сумма продаж за второй год"
                        box
                        ref="summa_prodazh_za_vtoroi_god"
                        :rules="[() => !!summa_prodazh_za_vtoroi_god || 'Обязательное поле']"
                        :placeholder="vtoroi_god_children_disabled?'Выберите второй год':''"
                        :disabled="vtoroi_god_children_disabled"
                        ></v-text-field>
                    </v-flex>
                    <v-flex xs12 md4>
                        <v-text-field
                        v-model="prodazhi_upakovok_za_vtoroi_god"
                        name="prodazhi_upakovok_za_vtoroi_god"
                        type="text"
                        label="Продажы упаковок за второй год"
                        box
                        ref="prodazhi_upakovok_za_vtoroi_god"
                        :rules="[() => !!prodazhi_upakovok_za_vtoroi_god || 'Обязательное поле']"
                        :placeholder="vtoroi_god_children_disabled?'Выберите второй год':''"
                        :disabled="vtoroi_god_children_disabled"
                        ></v-text-field>
                    </v-flex>
                    <v-flex xs12 md3>
                        <v-text-field
                        v-model="kppr"
                        name="kppr"
                        type="text"
                        label="КППР"
                        box
                        ref="kppr"
                        :rules="[() => !!kppr || 'Обязательное поле']"
                        ></v-text-field>
                    </v-flex>
                    <v-flex xs12 md3>
                        <v-text-field
                        v-model="dolya_bg"
                        name="dolya_bg"
                        type="text"
                        label="Доля БГ"
                        box
                        ref="dolya_bg"
                        :rules="[() => !!dolya_bg || 'Обязательное поле']"
                        ></v-text-field>
                    </v-flex>
                    <v-flex xs12 md3>
                        <v-text-field
                        v-model="dolya_mst"
                        name="dolya_mst"
                        type="text"
                        label="Доля Мст"
                        box
                        ref="dolya_mst"
                        :rules="[() => !!dolya_mst || 'Обязательное поле']"
                        ></v-text-field>
                    </v-flex>
                    <v-flex xs12 md3>
                        <v-text-field
                        v-model="prirost_mst"
                        name="prirost_mst"
                        type="text"
                        label="Прир Мст"
                        box
                        ref="prirost_mst"
                        :rules="[() => !!prirost_mst || 'Обязательное поле']"
                        ></v-text-field>
                    </v-flex>
                    <v-flex xs12 sm12>
                        <span class="subheading">НКПН</span>
                        <span v-if="nkpnSum > 100" class="red--text"> не должен превышать 100%</span>
                        <v-divider></v-divider>
                    </v-flex>
                    <v-flex xs12 sm12>
                        <v-container grid-list-sm pa-0>
                            <v-layout row>
                                <v-flex 
                                v-for="(i,key) in nkpn"
                                :key="'nkpn'+(key+1)"
                                > 
                                    <v-text-field
                                    v-model="nkpn[key]"
                                    :name="'nkpn'+(key+1)"
                                    type="number"
                                    :label="`${key+1}`"
                                    box
                                    :rules="[ nkpnSum <= 100 || '']"
                                    ></v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-flex>
                    <v-flex xs12 sm12>
                        <span class="subheading">НКПФ</span>
                        <span v-if="nkpfSum > 100" class="red--text"> не должен превышать 100%</span>
                        <v-divider></v-divider>
                    </v-flex>
                    <v-flex xs12 sm12>
                        <v-container grid-list-sm pa-0>
                            <v-layout row>
                                <v-flex 
                                v-for="(i,key) in nkpf"
                                :key="'nkpf'+(key+1)"
                                > 
                                    <v-text-field
                                    v-model="nkpf[key]"
                                    :name="'nkpf'+(key+1)"
                                    type="number"
                                    :label="`${key+1}`"
                                    box
                                    :rules="[ nkpfSum <= 100 || '']"
                                    ></v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-flex>
                    <v-flex>
                        <v-btn color="primary" @click="submit">Создать</v-btn>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-form>
    </div>
</template>

<script>
export default {
    data(){
        return {
            years:['2016','2017','2018','2019','2020','2021','2022'],

            mnn: null,
            mnns:[],
            mnnIsLoading:false,
            mnnSearch:null,

            form:null,
            forms:[],
            formSearch:null,
            formPlaceholder: "Сначало выберите МНН",
            formIsLoading:false,
            formDisabled:true,
            formHasErrors:false,

            perviy_god:null,
            perviy_god_children_disabled:true,
            vtoroi_god:null,
            vtoroi_god_children_disabled:true,
            
            doza:null,
            opu:null,
            thchp:null,
            summa_prodazh_za_perviy_god:null,
            prodazhi_upakovok_za_perviy_god:null,
            summa_prodazh_za_vtoroi_god:null,
            prodazhi_upakovok_za_vtoroi_god:null,
            kppr:null,
            dolya_bg:null,
            dolya_mst:null,
            prirost_mst:null,

            nkpn: Array(8),
            nkpnSum:0,
            
            nkpf: Array(8),
            nkpfSum:0,
            
        }
    },
    watch:{
        mnnSearch(val){
            if(!this.mnn){
                this.mnnIsLoading = true;
                let self = this;
                axios.get(`${window.Laravel.asset_path}api/mnns?search=${val}`)
                    .then(function(response){
                        self.mnns = response.data;
                        self.mnnIsLoading = false;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }
        },
        mnn(val){
            this.formDisabled = false;
            this.formIsLoading = true;
            let self = this;
            axios.get(`${window.Laravel.asset_path}api/forms?mnn_id=${val.id}`)
                 .then(function(response){
                    self.forms = response.data;
                    self.formIsLoading = false;
                    self.formPlaceholder = "";
                 })
                 .catch(function (error) {
                    console.log(error);
                });

            // console.log(val);
        },
        perviy_god(val){
            this.perviy_god_children_disabled = false;
        },
        vtoroi_god(val){
            this.vtoroi_god_children_disabled = false;
        }
    },
    computed:{
        formFields(){
            return {
                mnn:this.mnn,
                form:this.form,
                doza:this.doza,
                opu:this.opu,
                thchp:this.thchp,
                perviy_god:this.perviy_god,
                summa_prodazh_za_perviy_god:this.summa_prodazh_za_perviy_god,
                prodazhi_upakovok_za_perviy_god:this.prodazhi_upakovok_za_perviy_god,
                vtoroi_god:this.vtoroi_god,
                summa_prodazh_za_vtoroi_god:this.summa_prodazh_za_vtoroi_god,
                prodazhi_upakovok_za_vtoroi_god:this.prodazhi_upakovok_za_vtoroi_god,
                kppr:this.kppr,
                dolya_bg:this.dolya_bg,
                dolya_mst:this.dolya_mst,
                prirost_mst:this.prirost_mst
            }
        }
    },
    methods:{
        submit(){
            this.formHasErrors = false;

            Object.keys(this.formFields).forEach(f => {
            if (!this.formFields[f]) 
                this.formHasErrors = true

                this.$refs[f].validate(true)
            });
            this.nkpfSum=0;
            for (const item of this.nkpf) {
                if(item)
                    this.nkpfSum+=Number(item);
            }
            console.log(this.nkpfSum);
            
            
        },
        validate(){

        }
    }
}
</script>

<style>

</style>
