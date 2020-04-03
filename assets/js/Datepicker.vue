<template>
  <div class="content" >
    <div class="datepicker">
        <div class="datepicker_comment">
            <input  type="datetime"  name="temps" :value="format"  @select="select" @focus="open_datepicker"  />
            <datepicker-agenda class="d-agenda" :date="date" @select="select" v-if="bool==true"></datepicker-agenda>
        </div>

    </div>
  </div>
</template>
<script>
import Moment from "moment";
import { extendMoment } from "moment-range";
const moment = extendMoment(Moment);

import DatepickerAgenda from "./components/DatepickerAgenda";

export default {
  components: {
    DatepickerAgenda,
  },
  props: {
    value: String
  },
  data() {
    return {
      date: moment(),
      date_fin: moment(),
      bool: false,
      nbWeek: null,
    };
  },
  computed: {
    format() {
      return this.date.format("DD-MM-YYYY");
    },
    
    week() {
      const start = this.date;
      const end = this.date_fin;
      const range = moment.range(start, end);
      return range.diff("week");
    }
  },
  methods: {
    select(date) {
      this.date = date;
      this.bool = false;
    },

    open_datepicker() {
      this.bool = true;
      console.log(document.querySelector('.d-agenda'))
    },
    boolean_start() {
      this.bool = false;
    },
    
    close_datepicker() {
      this.bool = false;
    }
  }
};
</script>
<style lang="scss">
.datepicker_comment{
    position: relative;
    input{
        height: 30px;
    }

    .d-agenda{
        position: absolute;
        left:20px;
        top:40px;
    }

}
</style>