<template>
    <div class="hello" ref="chartdiv"></div>
</template>
<script>
import * as am4core from "@amcharts/amcharts4/core";
import * as am4charts from "@amcharts/amcharts4/charts";
import am4themes_animated from "@amcharts/amcharts4/themes/animated";

am4core.useTheme(am4themes_animated);

export default {
    props: {
        data: {
            required: true
        }
    },
    mounted() {
        let chart = am4core.create(this.$refs.chartdiv, am4charts.PieChart3D);
        chart.hiddenState.properties.opacity = 0; // this creates initial fade-in
        chart.legend = new am4charts.Legend();
        chart.data = this.data;
        chart.innerRadius = 100;
        let series = chart.series.push(new am4charts.PieSeries3D());
        series.dataFields.value = "value";
        series.dataFields.category = "text";
        this.chart = chart;
    },
    beforeDestroy() {
        if (this.chart) {
            this.chart.dispose();
        }
    }
};
</script>
<style>
.hello {
    width: 100%;
    height: 500px;
}
</style>
