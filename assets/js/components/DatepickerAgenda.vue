<template>
    <div class="agenda" >
        <div class="header">
            <div class="header_year">{{year}} </div>
            <div class="header_month">{{getMonth}} </div>
        </div>
        <div class="choise">
            <div class="next" @click="prev">
                <i class="material-icons">
                    keyboard_arrow_left
                </i>
            </div>
            <div class="choise_month"> {{month.getMonth()}} </div>
            <div class="prev" @click="next">
                <i class="material-icons">
                    keyboard_arrow_right
                </i>
            </div>
        </div>
        <div class="title_days" >
            <div class="title_day" v-for="day in days" :key="day"> {{day}} </div>
        </div>
        <div class="datepicker_days">
                <div class="datepicker_day" v-bind:style=" {width:(month.getFirst()*30) +'px'} "></div><!-- defini le style -->
                <div class="datepicker_day" v-for="day in month.getDays()" :key="day.toString()" :class="{selected: isSelected(day)}" @click="selectDate(day)" > 
                    <span class="effect"></span>
                    <span class="text">{{day.format('D')}}</span> 
                </div>
        </div>
    </div>
</template>
<script>
import moment from 'moment'
moment.locale('fr-FR')
import Month from './Month'
export default {
    props:['date'],
    data(){
        return {
            days:['L','M','Me','J','V','S','D'],
            month: new Month(this.date.month(), this.date.year())
        }
    },
    computed:{
        year(){
            return this.date.format('YYYY')
        },
        getMonth(){
            return this.date.format('DD MMMM')
        },
       
    },
    methods:{
        /**
         * Sélectionne le jour coché pour lui affilié un css
         */
        isSelected(day){
            return this.date.format('D') === day.format('D')
        },
        /**
         * Sélectionne un jour / remonte la date au composant parent
         */
        selectDate(day){
            this.date = day
            this.$emit('select',this.date)
        },
       
        prev(){
            let month = this.month.month - 1
            let year = this.month.year
            if(month<0){
                month = 11
                year = year -1
            }
            this.month = new Month(month, year)
        },
        next(){
            let month = this.month.month +1
            let year = this.month.year
            if(month>11){
                month = 0
                year = year + 1
            }
            this.month = new Month(month, year)
        }
    }
}
</script>
<style lang="scss">
.agenda{
    font-family: sans-serif;
    width: 230px;
    height:300px;
    box-shadow:0px 0px 5px black ;
    position: absolute;
    z-index: 100;
    background-color: #fff;
    
    .header{
        height: 80px;
        background-color: #87bb34;
        color:#fff;
        text-align: center;
        .header_year{
            padding:8px;
        }
        .header_month{
            padding: 0px;
        }
    }
    .choise{
        height: 24px;
        display: flex;
        justify-content: space-evenly;
       
        .prev{
            height: 32px;
            width: 32px;
            cursor:pointer;
        }
        .next{
            height: 32px;
            width: 32px;
            cursor:pointer;
        }
        .choise_month{
            text-align: center;
            color:black;
        }
    }
    
    .title_days{
        height: 16px;
        padding: 10px;
        .title_day{
            line-height: 16px;
            float:left;
            width: 30px;
            font-size:14px;
        }
    }
    .datepicker_days{
        position: relative;
        .datepicker_day{
            width:30px;
            height: 24px;
            float: left;
            text-align: center;
            position: relative;
            color:black;
        }
            .datepicker_day.selected{
           
               .effect{
                   display: block;
                    height: 30px;
                    width: 30px;
                    border-radius: 30px;
                    background-color: #87bb34;
                    position: absolute;
                    z-index: -1;
                    top:-3px;
               }
               .text{
                   color:white;
                   z-index: 10;
               }
                
            }
    }
}
    
</style>