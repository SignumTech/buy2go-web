<script>
import { Line } from 'vue-chartjs'

export default {
  extends: Line,
  props: {
    chartData: {
      type: Object,
      default: null
    },
    options: {
      type: Object,
      default: null,
    }
  },

  async mounted () {
    this.renderChart(this.chartData, this.options)
  }
}
</script>